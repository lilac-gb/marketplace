<?php

namespace api\components;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Yii;
use yii\web\IdentityInterface;
use Sentry;

/**
 * Class User
 * @package api\components
 * @property Token $token
 */
class User extends \yii\web\User
{
    public $token = null;

    public function getModel()
    {
        if (!$this->isGuest && !isset($this->_model)) {
            $user = new $this->identityClass();

            $this->_model = $user::findOne( $this->id );
        }

        return $this->_model;
    }

    public function getRoles()
    {
        return [];
//        return Yii::$app->authManager->getRolesByUser( $this->id );
    }

    protected function generateToken($userId = null)
    {
        if (!$userId) {
            $userId = $this->getId();
        }

        $issued = time();
        $expires = $issued + Yii::$app->params['jwt']['ttl'];

        $signer = $this->getSigner();

        //TODO: enable iss aud jti
        return (new Builder())
            //->canOnlyBeUsedBy('http://example.org') // Configures the audience (aud claim)
            //->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedBy(Yii::$app->params['jwt']['issuer'])
            ->issuedAt($issued) // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($issued) // Configures the time that the token can be used (nbf claim)
            ->expiresAt($expires) // Configures the expiration time of the token (nbf claim)
            ->with('uid', $userId)
            ->sign($signer, Yii::$app->params['jwt']['secret'])
            ->getToken();
    }

    public function refreshToken($userId = null)
    {
        if (!$userId) {
            $userId = $this->getId();
        }

        $this->token = $this->generateToken($userId);

        $tokenId = $this->getRedisTokenId($this->token);

        Yii::$app->redis->set($tokenId, $userId);
        Yii::$app->redis->expire($tokenId, Yii::$app->params['jwt']['ttl']);

        return $this->token;
    }

    public function getTokenResponse()
    {
        if (!$this->token) {
            return [];
        }

        return [
            'token' => (string) $this->token,
            'expires' => $this->token->getClaim('exp', null),
        ];
    }

    public function loginByAccessToken($token, $type = null) {
        Sentry\configureScope(function (Sentry\State\Scope $scope) use ($token) {
            $scope->setExtra('token', $token);
        });

        try {
            if (!$token) {
                return null;
            }

            $token = (new Parser())->parse((string) $token);

            $redisTokenId = $this->getRedisTokenId($token);
            $redisUserId = Yii::$app->redis->get($redisTokenId);

            /** @var ValidationData $validationData */
            $validationData = new ValidationData();
            $validationData->setIssuer(Yii::$app->params['jwt']['issuer']);

            $validationData->setCurrentTime($token->getClaim('nbf'));
            $signer = $this->getSigner();

            //check token issuer/signer
            if (!$token->validate($validationData) || !$token->verify($signer, Yii::$app->params['jwt']['secret'])) {
                Sentry\configureScope(function (Sentry\State\Scope $scope) use ($token, $validationData, $signer) {
                    $scope->setExtra('validate', $token->validate($validationData));
                    $scope->setExtra('verify', $token->verify($signer, Yii::$app->params['jwt']['secret']));
                    $scope->setExtra('signer', $signer);
                    $scope->setExtra('secret', Yii::$app->params['jwt']['secret']);
                });

                throw new \Exception('JWT token: invalid token');
            }

            //if jwt token expires, but exist in redis - generate new token and save it
            $validationData->setCurrentTime(time());

            if ($token->isExpired()) {
                if ($redisUserId) {
                    Yii::$app->redis->del($redisTokenId);

                    $this->logout();
                    $token = $this->refreshToken($redisUserId);
                    // TODO: renew $redisUserId to continue processing token
                }
            }

            if (!$redisUserId) {
                throw new \Exception('JWT token: not found in redis');
            }

            /* @var $class IdentityInterface */
            $class = $this->identityClass;
            $identity = $class::findIdentityByAccessToken($token, $type);

            if ($identity && $this->login($identity)) {
                return $identity;
            }

            throw new \Exception('JWT token: user not found');
        } catch (\Exception $e) {
            Yii::error($e);
            Sentry\captureException($e);

            return null;
        }
    }

    protected function getSigner()
    {
        $signer = null;

        switch (Yii::$app->params['jwt']['alg']) {
            case 'HS512':
                $signer = new Sha512();
                break;

            case 'HS256':
            default:
                $signer = new Sha256();
                break;
        }

        return $signer;
    }

    public function getRedisTokenId($token)
    {
        return 'access_token:' . (string) $token;
    }
}
