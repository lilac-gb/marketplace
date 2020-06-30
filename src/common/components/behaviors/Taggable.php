<?php

namespace common\components\behaviors;

use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\Query;

class Taggable extends \dosamigos\taggable\Taggable
{
    /**
     * @var string|null
     */
    public $frequency = 'frequency';

    /** @var string $primaryField */
    public $primaryField = 'id';

    /**
     * @param Event $event
     */
    public function afterSave($event)
    {
        if ($this->tagValues === null) {
            $this->tagValues = $this->owner->{$this->attribute};
        }

        if (!$this->owner->getIsNewRecord()) {
            $this->beforeDelete($event);
        }

        $names = [];

        if (!empty($this->tagValues)) {
            foreach ($this->tagValues as $tagValue) {
                if (!isset($names[$tagValue])) {
                    $names[$tagValue] = $tagValue;
                }
            }
        }

        $relation = $this->owner->getRelation($this->relation);


        /** @var ActiveRecord $class */
        if (gettype($relation->via) == 'array') {
            $class = $relation->via[1]->modelClass;
            $pivot = $class::tableName();
            $viaLink = $relation->via[1]->link;
        } else {
            $pivot = $relation->via->from[0];
            $viaLink = $relation->via->link;
        }

        $link = $relation->link;


        $class = $relation->modelClass;
        $rows = [];
        $updatedTags = [];

        foreach ($names as $name) {
            if ($this->asArray) {
                $tag = $class::findOne($name);
            } else {
                $tag = $class::findOne([$this->name => $name]);
            }
            //$tag = $class::findOne([$this->name => $name]);

            if ($tag === null) {
                $tag = new $class();
                $tag->{$this->name} = $name;
            }

            if (isset($this->frequency)) {
                $tag->{$this->frequency}++;
            }

            if ($tag->save()) {
                $updatedTags[] = $tag;
                $rows[] = [$this->owner->{$this->primaryField}, $tag->getPrimaryKey()];
            }
        }

        if (!empty($rows)) {
            $this->owner->getDb()
                ->createCommand()
                ->batchInsert($pivot, [key($viaLink), current($link)], $rows)
                ->execute();
        }

        $this->owner->populateRelation($this->relation, $updatedTags);
        $this->owner->{$this->attribute} = [];

        foreach($updatedTags as $tag) {
            $this->owner->{$this->attribute}[$tag->id] = $tag->name;
        }
    }

    /**
     * @param Event $event
     */
    public function beforeDelete($event)
    {
        $relation = $this->owner->getRelation($this->relation);

        if (gettype($relation->via) == 'array') {
            $classVia = $relation->via[1]->modelClass;
            $pivot = $classVia::tableName();
            $where = key($relation->via[1]->link);
        } else {
            $pivot = $relation->via->from[0];
            $where = key($relation->via->link);
        }


        /** @var ActiveRecord $class */
        $class = $relation->modelClass;
        $select = current($relation->link);


        $query = new Query();
        $pks = $query
            ->select($select)
            ->from($pivot)
            ->where([$where => $this->owner->{$this->primaryField}])
            ->column($this->owner->getDb());

        if (!empty($pks) && isset($this->frequency)) {
            $class::updateAllCounters([$this->frequency => -1], ['in', $class::primaryKey(), $pks]);
        }

        $this->owner->getDb()
            ->createCommand()
            ->delete($pivot, [$where => $this->owner->{$this->primaryField}])
            ->execute();
    }

    public function __get($name)
    {
        return $this->getTagNames();
    }

    public function __set($name, $value)
    {
        $this->tagValues = $value;
        $this->owner->{$name} = $value;
    }

    private function getTagNames()
    {
        $items = [];

        $tags=$this->owner->{$this->relation};

        if (is_array($tags)){
            foreach ($tags as $tag) {
                $items[$tag->id] = $tag->{$this->name};
            }
        }

        return $this->asArray ? $items : implode(',', $items);
    }
}