;(function ($) {
    $.fn.syncTranslit = function (options) {
        let opts = $.extend({}, $.fn.syncTranslit.defaults, options);
        return this.each(function () {
            $this = $(this);
            let o = $.meta ? $.extend({}, opts, $this.data()) : opts;
            let $destination = $('#' + opts.destination);
            o.destinationObject = $destination;
            if (!Array.indexOf) {
                Array.prototype.indexOf = function (obj) {
                    for (let i = 0; i < this.length; i++) {
                        if (this[i] === obj) {
                            return i;
                        }
                    }
                    return -1;
                }
            }
            $this.keyup(function () {
                let str = $(this).val();
                let result = '';
                for (let i = 0; i < str.length; i++) {
                    result += $.fn.syncTranslit.transliterate(str.charAt(i), o)
                }
                let regExp = new RegExp('[' + o.urlSeparator + ']{2,}', 'g');
                result = result.replace(regExp, o.urlSeparator);
                result = result.slice(0, 80);
                $destination.val(result);
                $destination.trigger('change');
            })
        });
    };
    $.fn.syncTranslit.transliterate = function (char, opts) {
        let charIsLowerCase = true, trChar;
        if (char.toLowerCase() !== char) {
            charIsLowerCase = false;
        }
        char = char.toLowerCase();
        let index = opts.dictOriginal.indexOf(char);
        if (index === -1) {
            trChar = char;
        } else {
            trChar = opts.dictTranslate[index];
        }
        if (opts.type === 'url') {
            let code = trChar.charCodeAt(0);
            if (code >= 33 && code <= 47 && code !== 45
                || code >= 58 && code <= 64
                || code >= 91 && code <= 96
                || code >= 123 && code <= 126
                || code >= 1072
            ) {
                return '';
            }
            if (trChar === ' ' || trChar === '-') {
                return opts.urlSeparator;
            }
        }
        if (opts.caseStyle === 'upper') {
            return trChar.toUpperCase();
        } else if (opts.caseStyle === 'normal') {
            if (charIsLowerCase) {
                return trChar.toLowerCase();
            } else {
                return trChar.toUpperCase();
            }
        }
        return trChar;
    };
    $.fn.syncTranslit.defaults = {
        dictOriginal: ['а', 'б', 'в', 'г', 'д', 'е',
            'ё', 'ж', 'з', 'и', 'й', 'к',
            'л', 'м', 'н', 'о', 'п', 'р',
            'с', 'т', 'у', 'ф', 'х', 'ц',
            'ч', 'ш', 'щ', 'ъ', 'ы', 'ь',
            'э', 'ю', 'я',
            'і', 'є', 'ї', 'ґ','"', '\'', '»', '«', '_'
        ],
        dictTranslate: ['a', 'b', 'v', 'g', 'd', 'e',
            'e', 'zh', 'z', 'i', 'j', 'k',
            'l', 'm', 'n', 'o', 'p', 'r',
            's', 't', 'u', 'f', 'h', 'ts',
            'ch', 'sh', 'sch', '', 'y', '',
            'e', 'ju', 'ja',
            'i', 'je', 'ji', 'g','', '', '', '','-'
        ],
        caseStyle: 'lower',
        urlSeparator: '-',
        type: 'url'
    };
})(jQuery);