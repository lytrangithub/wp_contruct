(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
  typeof define === 'function' && define.amd ? define(factory) :
  (global = global || self, global.Libs = factory());
}(this, function () {
    'use strict';
    //screen size on mobile
    var breakPoint = 768;
    //Sidebar width on mobile
    var leftPosition = 250;
    var Libs = {};

    /**
    * Get url
    *
    * @param {string} pagePath
    * @return {string} url
    */
    Libs.url = function(pagePath)
    {
        if(Libs.isBlank(pagePath)) return baseUrl;
        return baseUrl+pagePath;
    }

    /**
     * Get param string by form id
     *
     * @param {string} formid: form id
     * @returns {string} string params
     */
     Libs.getParamString = function(formid) {

        if(Libs.isBlank(formid)) return "";
        var frm = document.getElementById(formid);
        if(Libs.isBlank(frm)) return "";

        var param = "";
        for (var i = 0; i < frm.length; i++) {
            var element = frm.elements[i];
            if (element.tagName.toLowerCase() == "input"
                || element.tagName.toLowerCase() == "select"
                || element.tagName.toLowerCase() == "textarea") {
                if (frm.elements[i].type.toLowerCase() == "checkbox"
                    || frm.elements[i].type.toLowerCase() == "radio") {
                    if (frm.elements[i].checked) {
                        if (param == "")
                            param = frm.elements[i].name + "="
                                + Libs.encodeURL(frm.elements[i].value);
                        else
                            param += "&" + frm.elements[i].name + "="
                                + Libs.encodeURL(frm.elements[i].value);
                    }
                } else if (element.type.toLowerCase() != "file") {
                    if (param == "")
                        param = frm.elements[i].name + "="
                            + Libs.encodeURL(frm.elements[i].value);
                    else
                        param += "&" + frm.elements[i].name + "="
                            + Libs.encodeURL(frm.elements[i].value);
                }
            }
        }
        return param;
    };

    /**
     * Reset form
     *
     * @param {string} formid
     * @param {array} dataArray
     * @return void
     */
    Libs.resetForm = function(formid, dataArray) {
      var frm = document.getElementById(formid);
      for (var i = 0; i < frm.length; i++) {
        var element = frm.elements[i];
        if (element.tagName.toLowerCase() == "input"
            || element.tagName.toLowerCase() == "select"
            || element.tagName.toLowerCase() == "textarea") {
          if (frm.elements[i].type.toLowerCase() == "checkbox"
              || frm.elements[i].type.toLowerCase() == "radio") {
            frm.elements[i].checked = false;

          } else {
            if(dataArray && dataArray.length > 0){
              for(var j = 0; j < dataArray.length; j++){
                if(frm.elements[i].name != dataArray[j])
                  frm.elements[i].value = "";
              }
            }else{
              frm.elements[i].value = "";
            }
          }
        }
      }
    };

    /**
     * Encode URL
     *
     * @param {string} result
     * @return {string} url encode
     */
    Libs.encodeURL = function(result) {
        var encodeString = encodeURI(result);
        encodeString = encodeString.replace('!', '%21');
        encodeString = encodeString.replace('#', '%23');
        encodeString = encodeString.replace('$', '%24');
        encodeString = encodeString.replace(/&/g, '%26');
        encodeString = encodeString.replace('(', '%28');
        encodeString = encodeString.replace(')', '%29');
        encodeString = encodeString.replace('?', '%3F');
        encodeString = encodeString.replace('?', '%3F');
        encodeString = encodeString.replace(/\+/g, '%2B');
        encodeString = encodeString.replace(/\"/g, '%22');
        encodeString = encodeString.replace(/\'/g, '%27');
        return encodeString;
    };

    Libs.safeTrim = function(str){
        try {
            return (typeof str === 'string') ? str.trim() : str.toString();
        } catch (e) {
            return "";
        }
    };

    Libs.safeString = function(str){
        try {
            if(Libs.isBlank(str)) return "";
            return str;
        } catch (e) {
            return "";
        }
    };

    Libs.rEnter = function(event){
        var _this = $(event.target);
        if (event.which === 13) {
            var sign = event.shiftKey ? -1 : 1;
            event.preventDefault();
            var fields = _this.parents('form:eq(0),body').find('input,textarea');
            var index = fields.index(_this);
            if (index > -1 && (index + 1 * sign) < fields.length)
                fields.eq(index + 1 * sign).focus();
        }
    }

    /**
     * Check blank object or string
     *
     * @param str
     * @returns {boolean}
     */
    Libs.isBlank = function(str){
        if (typeof str === undefined || str == null || Libs.safeTrim(str) === "") {
            return true;
        }
        return false;
    }

    /**
     * Checks whether an array exists and has data
     *
     * @param Array arr
     * @return {boolean}
     */
    Libs.isArrayData = function(arr) {
        if (Libs.isBlank(arr)) return false;
        if (!Array.isArray(arr) || arr.length <= 0) return false;
        return true;
    }

    /**
     * Get param by key
     *
     * @param {string} k: param name
     * @return param value
     */
    Libs.getSearchParams = function(k){
        var p={};
        location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v});
        if(Libs.isBlank(p[k])) return '';
        return k?p[k]:p;
    }

    /**
     * Get param by key
     *
     * @param {string} k: param name
     * @return param value
     */
    Libs.getParamsOnUrl = function(){
        var params = window.location.href.split('/');
        return params;
    }

    /**
     * Convert html entity string to html
     *
     * @param {string} text: html entity
     * @return {string} html
     */
    Libs.decodeHTMLEntities = function(text) {
        var entities = [
            ['amp', '&'],
            ['apos', '\''],
            ['#x27', '\''],
            ['#x2F', '/'],
            ['#39', '\''],
            ['#47', '/'],
            ['lt', '<'],
            ['gt', '>'],
            ['nbsp', ' '],
            ['quot', '"']
        ];
        for (var i = 0, max = entities.length; i < max; ++i)
            text = text.replace(new RegExp('&'+entities[i][0]+';', 'g'), entities[i][1]);

        return text;
    }

    /**
     * Insert param to url
     *
     * @param {string} key param key
     * @return {string} new url
     */
    Libs.insertParam = function(key, value) {
        if (history.pushState) {
            var currentUrl = window.location.href;
            //remove any param for the same key
            var currentUrl = Libs.removeURLParameter(currentUrl, key);

            //figure out if we need to add the param with a ? or a &
            var queryStart;
            if(currentUrl.indexOf('?') !== -1){
                queryStart = '&';
            } else {
                queryStart = '?';
            }

            var newurl = currentUrl + queryStart + key + '=' + value
            window.history.pushState({path:newurl},'',newurl);
        }
    }

    /**
     * Remove url param
     *
     * @param {string} url
     * @param {string} key param key
     * @return {string} new url
     */
    Libs.removeURLParameter = function(url, key) {
        //better to use l.search if you have a location/link object
        var urlparts= url.split('?');
        if (urlparts.length>=2) {

            var prefix= encodeURIComponent(key)+'=';
            var pars= urlparts[1].split(/[&;]/g);

            //reverse iteration as may be destructive
            for (var i= pars.length; i-- > 0;) {
                //idiom for string.startsWith
                if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                    pars.splice(i, 1);
                }
            }

            url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
            return url;
        } else {
            return url;
        }
    }

    /**
    * Check is number
    *
    * @param int number
    * @return boolean
    */
    Libs.isNumeric = function (number) {
        if (Libs.isBlank(number)) {
            return false;
        }
        return $.isNumeric(number);
    }

    /**
     * @description Tạo chuỗi mã hóa 8 ký tự bao gồm chữ và số 
     * @author: Luyen Nguyen
     * @return str
     */
    Libs.strRandom = function (chr) {
        if (!Libs.isNumeric(chr)) {
            chr = 6;
        }
        var numberChr = '0x'+Math.pow(10, chr);
        return Math.floor((1 + Math.random()) * numberChr).toString(16).substring(1);
    }

    Libs.fncInputNumberAllowComma = function(e) { // IE,FireFox
        if (window.event) {
            if (e.keyCode < 40 || e.keyCode > 57 || e.keyCode == 47
                || e.keyCode == 44 || e.keyCode == 42 || e.keyCode == 43) {
                // if(e.keyCode != 59)
                Libs.fncNonInput(e);
                return false;
            }
        } else {
            if (e.which == 8 || e.which == 0 || e.which == 13) {
                return true;
            }
            if (e.which < 40 || e.which > 57 || e.which == 47 || e.which == 44
                || e.which == 42 || e.which == 43) {
                // if(e.which != 59)
                Libs.fncNonInput(e);
                return false;
            }
        }
        return true;
    }

    Libs.fncInputOnlyNumberAllowComma = function(e) { // IE,FireFox
        if (window.event) {
            if (e.keyCode == 46) {
                Libs.fncNonInput(e);
                return false;
            }
            if (e.keyCode < 40 || e.keyCode > 57 || e.keyCode == 47
                || e.keyCode == 44 || e.keyCode == 190 || e.keyCode == 42
                || e.keyCode == 43) {
                // if(e.keyCode != 59)
                Libs.fncNonInput(e);
                return false;
            }
        } else {
            if (e.which == 46) {
                Libs.fncNonInput(e);
                return false;
            }
            if (e.which == 8 || e.which == 0 || e.which == 13) {
                return true;
            }
            if (e.which < 40 || e.which > 57 || e.which == 47 || e.which == 44
                || e.which == 42 || e.which == 43) {
                // if(e.which != 59)
                Libs.fncNonInput(e);
                return false;
            }
        }
        return true;
    }
    Libs.fncOnlyDitgit = function(e) { // IE,FireFox
        if (window.event) {
            if (e.keyCode == 46) {
                Libs.fncNonInput(e);
                return false;
            }
            if (e.keyCode < 40 || e.keyCode > 57 || e.keyCode == 47
                || e.keyCode == 44 || e.keyCode == 190 || e.keyCode == 42
                || e.keyCode == 43 || e.keyCode == 45 || e.keyCode == 219
                || e.keyCode == 221) {
                // if(e.keyCode != 59)
                Libs.fncNonInput(e);
                return false;
            }
        } else {
            if (e.which == 46) {
                Libs.fncNonInput(e);
                return false;
            }
            if (e.which == 8 || e.which == 0 || e.which == 13) {
                return true;
            }
            if (e.which < 40 || e.which > 57 || e.which == 47 || e.which == 44
                || e.which == 42 || e.which == 43 || e.which == 45
                || e.keyCode == 219 || e.keyCode == 221) {
                // if(e.which != 59)
                Libs.fncNonInput(e);
                return false;
            }
        }
        return true;
    }

    Libs.fncNonInput = function(ev) { // IE/Firefox
        if (window.event) {
            ev.returnValue = false;
            ev.cancelBubble = true;
            ev.preventDefault();
        } else {
            ev.preventDefault();
            ev.stopPropagation();
        }
    }
    Libs.convert2Money = function(price) {
        var price_str = price + " ";
        var dot_index = price_str.indexOf(".");
        
        var odd_umbers = "";
        var even_numbers = price+"";
        if(dot_index >= 0) {
            odd_umbers = price_str.slice(dot_index, -1);
            odd_umbers = odd_umbers.substring(0, 3);
            even_numbers = price_str.substring(0, dot_index);
        }
        
        // Convert phan nguyen ra dang tien:
        var result = "";
        if(price == 0) return "0";
        var price_temp = even_numbers+"";
        var max = even_numbers.length;
        var flag = max-3;
        if(flag < 0)flag = 0;
        for(var i=max; i > 0; i -= 3) {                 
            var a = price_temp.substring(flag, i);          
            result = a +","+result;
            if(i < 0)i = 0;
            flag -= 3;
            if(flag < 0)flag = 0;
        }
        return result.substring(0, result.length-1) + odd_umbers;
        //return result.substring(0, result.length-1);
    }

    /**
     * Find objects in the array by value and field
     *
     * @param array items
     * @param string field
     * @param string value
     * @return boolean|object
     */
    Libs.find = function (items, field, value, isIndex) {
        if (!items)
            return null;
        for (var i = 0; i < items.length; i++) {
            if (value == items[i][field]) {
                if (Libs.isBlank(isIndex)) {
                    return items[i];
                }
                return i;
            }
        }
        return null;
    };

    /**
     * Get coutn item in object
     *
     * @param object obj
     * @return int
     */
    Libs.getCountObject = function(obj)
    {
        if(Libs.isEmptyObject(obj)) return 0;
        return Object.keys(obj).length;
    }

    /**
     * Check is object
     *
     * @param object obj
     * @return boolean
     */
    Libs.isEmptyObject = function(obj){
        return typeof obj !== "object" || Libs.isBlank(obj);
    }

    /**
    * Split String with white space
    *
    * @param string str
    * @return string
    */
    Libs.strSplitSpace = function(str){
        if(Libs.isBlank(str)) return;
        return str.split(/[\s ]+/);
    }

    /**
    * Replace validation message
    *
    * @param string str
    * @param string srtInput
    * @param string strReplace
    * @return string
    */
    Libs.strReplace = function(str, srtInput, strReplace){

        if(Libs.isBlank(str) || Libs.isBlank(srtInput)) return;
        return str.replace(strReplace, srtInput);
    }

    /**
    * Replace line breaks
    *
    * @param string str
    * @param string strReplace  Character wants to replace to
    * @return string
    */
    Libs.strReplaceLineBreaks = function(str, strReplace){

        if(Libs.isBlank(str)) return;
        if (Libs.isBlank(strReplace)) {
            strReplace = " ";
        }
        return str.replace(new RegExp('\r?\n|&nbsp;','g'), strReplace);
    }

    /**
    * Get ascii character in string
    *
    * @param string str
    * @param string strReplace  Character wants to replace to
    * @return string
    */
    Libs.getASCIIChar = function(str, strReplace){

        if (Libs.isBlank(str)) {
            return "";
        }
        if (Libs.isBlank(strReplace)) {
            strReplace = " ";
        }
        str = Libs.strTrim(str);
        // UNICODE RANGE : DESCRIPTION
        // 
        // 3000-303F : punctuation
        // 3040-309F : hiragana
        // 30A0-30FF : katakana
        // FF00-FFEF : Full-width roman + half-width katakana
        // 4E00-9FAF : Common and uncommon kanji
        // 
        // Non-Japanese punctuation/formatting characters commonly used in Japanese text
        // 2605-2606 : Stars
        // 2190-2195 : Arrows
        // u203B     : Weird asterisk thing
        var regex = /[\u3000-\u303F]|[\u3040-\u309F]|[\u30A0-\u30FF]|[\uFF00-\uFFEF]|[\u4E00-\u9FAF]|[\u2605-\u2606]|[\u2190-\u2195]|\u203B/g; 
        return str.replace(regex, strReplace);
        //return str.replace(/[^\x00-\xFF]/g, strReplace);
    }

    /**
    * Get non ascii character in string
    *
    * @param string str
    * @return string
    */
    Libs.getNonASCIIChars = function(str){

        if (Libs.isBlank(str)) {
            return "";
        }
        str = Libs.strTrim(str);
        // UNICODE RANGE : DESCRIPTION
        // 
        // 3000-303F : punctuation
        // 3040-309F : hiragana
        // 30A0-30FF : katakana
        // FF00-FFEF : Full-width roman + half-width katakana
        // 4E00-9FAF : Common and uncommon kanji
        // 
        // Non-Japanese punctuation/formatting characters commonly used in Japanese text
        // 2605-2606 : Stars
        // 2190-2195 : Arrows
        // u203B     : Weird asterisk thing
        var regex = /[\u3000-\u303F]|[\u3040-\u309F]|[\u30A0-\u30FF]|[\uFF00-\uFFEF]|[\u4E00-\u9FAF]|[\u2605-\u2606]|[\u2190-\u2195]|\u203B/g; 
        return str.match(regex);
        //return str.match(/[^\x00-\xFF]/g);
    }


    /**
    * Remove white spaces in string
    *
    * @param string str
    * @return string
    */
    Libs.strTrim = function(str){
        return str.replace(/^\s+|\s+$/gm,'');
    }

    /**
    * Replace multiple spaces with a single space
    *
    * @param string str
    * @return string
    */
    Libs.strSingleSpace = function(str){
        if (Libs.isBlank(str)) {
            return "";
        }
        return str.replace(/^\s+|\s+$|\s+(?=\s)/g, "");
    }
    
    /**
    * Delete accented characters in Vietnamese
    *
    * @param string str
    * @return string
    */
    Libs.replaceAccents = function(str) {
        if (Libs.isBlank(str)) {
            return "";
        }
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
        str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
        str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
        str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
        str = str.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
        str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
        str = str.replace(/Đ/g, "D");
        return str;
        // return str.normalize('NFD')
        //     .replace(/[\u0300-\u036f]/g, '')
        //     .replace(/đ/g, 'd').replace(/Đ/g, 'D');
    }

    /**
    * Remove all white spaces in an array
    *
    * @param array arr
    * @return array
    */
    Libs.trimArraySpaces = function(arr){
        if(undefined === arr || null == arr) return;
        $.each( arr, function( key, value ) {
            if (typeof value === 'string' || value instanceof String)
            {
                arr[key] = Libs.strTrim(value);
            }
        });
        return arr;
    }

    /**
    * Line Breaks in Strings
    *
    * @param string str
    * @param boolean is_xhtml
    *
    * example 1: nl2br('Kevin\nvan\nZonneveld');
    * returns 1: 'Kevin<br />\nvan<br />\nZonneveld'
    * example 2: nl2br("\nOne\nTwo\n\nThree\n", false);
    * returns 2: '<br>\nOne<br>\nTwo<br>\n<br>\nThree<br>\n'
    * example 3: nl2br("\nOne\nTwo\n\nThree\n", true);
    * returns 3: '<br />\nOne<br />\nTwo<br />\n<br />\nThree<br />\n'
    * @return string
    */
    Libs.nl2br = function (str, is_xhtml) {
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }

    /**
    * Check startDate <= endDate
    *
    * @param string startDate  format yyyy/mm/dd or yyyy-mm-dd
    * @param string endDate  format yyyy/mm/dd or yyyy-mm-dd
    * @param string formatStartDate
    * @param string formatEndDate
    * @return array
    */
    Libs.checkStartEndDate = function (startDate, endDate, formatStartDate, formatEndDate) {
        var d1 = startDate;
        var d2 = endDate;
        if (typeof (startDate) === 'string') {
            formatStartDate = formatStartDate || Libs.getDateTimeFormat();
            d1 = Date.parse(startDate, formatStartDate);
        }
        if (typeof (endDate) === 'string') {
            formatEndDate = formatEndDate || Libs.getDateTimeFormat();
            d2 = Date.parse(endDate, formatEndDate);
        }
        return d1 <= d2;
    };

    /**
     * Get date format
     */
    Libs.getDateFormat = function (format) {
        if(!Libs.isBlank(format)){
            return format;
        }
        return 'yyyy.mm.dd';
    }

     /**
     * Get date format
     */
    Libs.getDateTimeFormat = function (format) {
        if(!Libs.isBlank(format)){
            return format;
        }
        return 'yyyy.mm.dd H:i:s';
    }

    Libs.getTime = function () {
        var d = new Date();
        return d.getTime();
    }

    /**
     * conver string to date
     */
    Libs.strToDateYYYMMDD = function (str) {
        var arr = str.split('.');
        if(arr.length == 3){
            return new Date(arr[0], arr[1], arr[2]);
        }
        return null;
    }

    /**
    * Set height item group
    *
    * @param string str
    * @return string
    */
    Libs.setContentHeight = function(ele, isInnerHeight){

        if(Libs.isBlank(ele) || !$(ele).length) return;
        var maxH = Libs.getMaxHeightElement(ele, isInnerHeight);
        $(ele).css({'height': maxH});
        $(window).resize(function(){
            $(ele).css({'height':'auto'});
            var maxH = Libs.getMaxHeightElement(ele, isInnerHeight);
            $(ele).css({'height': maxH});
        });
    }
    Libs.getMaxHeightElement = function(ele, isInnerHeight){
        var arrH = [];
        $(ele).each(function() {
            var itemH =  parseInt($( this ).height());
            if (!Libs.isBlank(isInnerHeight))
            {
                itemH = parseInt($( this ).innerHeight());
            }
          arrH.push(itemH);
        });
        return Math.max.apply(Math, arrH);
    }
    /**
     * Submit form
     *
     * @param string url
     * @param string formData
     * @returns void
     */
    var frmID = 0;
    Libs.submitFormAction = function(url, formData) {
        var form = document.createElement('form'),
            hiddenToken = document.createElement("input"),
            hiddenEle = document.createElement("input");
        //Token
        hiddenToken.setAttribute("type", "hidden");
        hiddenToken.setAttribute("name", "_token");
        hiddenToken.setAttribute("value", $('meta[name="csrf-token"]').attr('content'));
        //data input
        hiddenEle.setAttribute("type", "hidden");
        hiddenEle.setAttribute("name", "form_data");
        hiddenEle.setAttribute("value", formData);
        form.setAttribute("name", "hdn_frm_submit"+ ++frmID);
        form.setAttribute("action", url);
        form.setAttribute("method", "post");
        form.appendChild(hiddenToken);
        form.appendChild(hiddenEle);
        document.body.appendChild(form);
        form.submit();
        form.remove();
    }

    /**
     * @description Create popup showing the slide show
     *
     * @param data html or text
     * @param maxWidth px or %
     * @return String html
     */
    var globalID = 0;
    Libs.openModal = function(title, data, funCallback){
        var popupTpl = jQuery('<div></div>');
        popupTpl.attr("id", "fl-modal" + ++globalID);
        popupTpl.attr("class", "fl-modal");
        if (Libs.isBlank(title)) {
            title='';
        }
        var screen_height = window.innerHeight, html = jQuery('html');
        var modalHtml = '<div class="fl-modal-inner">';
            modalHtml += '<div class="fl-modal-title">'+title+'</div>';
            modalHtml += '<div class="fl-modal-close"><i class="fa fa-times" aria-hidden="true"></i></div>',
            modalHtml +='<div class="fl-modal-content"> ' +data+'</div><div class="fl-modal-backdrop"></div>';
            modalHtml += '</div>';
        jQuery(document.body).append(popupTpl);
        popupTpl.html(modalHtml);
        var _this = popupTpl;
        var screenW = window.innerWidth;
        var popupW = popupTpl.outerWidth();
        var popupH = popupTpl.outerHeight();
        if (!Libs.isBlank(title)) {
            var titleH = popupTpl.find('.fl-modal-title').outerHeight();
            popupTpl.find('.fl-modal-content').css({
                top: titleH
            });
        }
        jQuery('.fl-modal-close, .fl-modal-backdrop').on('click',
        function () {
            globalID--;
            _this.remove();
            if (typeof funCallback ===  'function') {
                funCallback();
            }
        });
        return _this;
    }

    /**
     * Get format file size
     *
     * @param int bytes
     * @param int decimalPoint
     * @returns string
     */
    Libs.formatFileSize = function(bytes, decimalPoint) {
       if(bytes == 0) return '0 Bytes';
       var k = 1000,
           dm = (Libs.isBlank(decimalPoint)) ? 2 : decimalPoint,
           sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
           i = Math.floor(Math.log(bytes) / Math.log(k));
       return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    /**
    * Get the file extension
    *
    * @param  string fileName
    * @return string
    */
    Libs.getExtensionFile = function (fileName) {
        if (fileName === 'undefined' || fileName == null) return;
        var ext = fileName.substr((fileName.lastIndexOf('.') + 1));
        return ext;
    }
    Libs.imagesLoaded = function(elem, options) {
        var images = $(elem).find("img"), 
            loadedImages = [];
        if (images.length <= 0) {
            return;
        }
        images.each(function(i, image) {
            function loaded() {
                loadedImages.push($(this));
                if(options.imageLoaded) {
                    options.imageLoaded(this);    
                }
                if(loadedImages.length == images.length) {
                    if(options.complete) {
                        options.complete(loadedImages);    
                    }
                }
            }
            if(image.complete || image.complete === undefined) {
                // Image is already loaded
                loaded.call(image);               
            } else {
                // Image is not loaded yet, bind event
                $(image).on('load',loaded);
            }
        });
    }
    Libs.getCurrentDate = function () {
        let date = new Date();
        let year = date.getFullYear().toString();
        let month = (date.getMonth() + 1).toString().padStart(2, "0");
        let day = date.getDate().toString().padStart(2, "0");
        return year + "/" + month + "/" + day;
    }

    Libs.getCurrentDatetime = function (format) {
        let date = new Date();
        let year = date.getFullYear().toString();
        let month = (date.getMonth() + 1).toString().padStart(2, "0");
        let day = date.getDate().toString().padStart(2, "0");
        let hour = date.getHours().toString().padStart(2, "0");
        let mi = date.getMinutes().toString().padStart(2, "0");
        let ss = date.getSeconds().toString().padStart(2, "0");
        if (Libs.isBlank(format)) {
            format = '/';
        }
        return year + format + month + format + day + " " + hour + ":" + mi;
    }

    /**
    * Check the upload file extension
    *
    * @param  string fileName
    * @param  array extArr  array containing file extensions
    * @return boolean
    */
    Libs.checkExtensionFile = function (fileName, extArr) {
        if (Libs.isBlank(fileName)) {
            return;
        }
        var extFileRequired = ['docx', 'xls', 'xlsx'];
        if (Libs.isArrayData(extArr)) {
            extFileRequired = extArr;
        }
        fileName = fileName.toLowerCase();
        var ext = fileName.substr((fileName.lastIndexOf('.') + 1));
        for (var i = 0; i < extFileRequired.length; i++) {
            if (ext === extFileRequired[i]) {
                return true;
            }
        }
        return false;
    }

    /**
    * Check for comparison values that exist in the array
    *
    * @param array arr ex: [1,3,5,...]
    * @param object value comparison value exists in an array
    * @return boolean
    */
    Libs.checkValueExistInArray = function (arr, value) {
        if (!Libs.isArrayData(arr)) {
            return;
        }
        return $.inArray( value, arr )*1 !== -1;
    }

    /**
    * Check scroll bottom by element
    *
    * @param object elem            The html tag to scroll to the bottom
    * @param function fnCalback     Call back function when scrolling to the bottom
    * @param int offset             he scroll distance is close to the bottom
    * @return void
    */
    Libs.scrollBottom = function (elem, fnCalback, offset) {
        if (jQuery(elem).length <= 0) {
            return;
        }
        if (!offset) {
            offset = 10;
        }
        jQuery(elem).on('scroll', function() {
            if(Math.round($(this).scrollTop() + $(this).innerHeight(), 10) >= Math.round($(this)[0].scrollHeight, 10) - offset) {
                if (typeof fnCalback === 'function') {
                    fnCalback();
                }
            }
        });
    }

    /**
     * Change Selected option in Select2 Dropdown
     *
     * @param string ele  html tag or attr
     * @param array options: extend more options
     * @returns object select2
     */
    Libs.selectDropdown = function(ele, options) {
        if (Libs.isBlank(ele)) {
            ele = 'select';
        }
        if (!jQuery.fn.select2) {
            return;
        }
        var selector = jQuery(ele);
        if (selector.length <= 0) {
            selector = jQuery('.'+ele);
            if(selector.length <= 0){
                selector = jQuery('#' + ele);
            }
        }
        if (selector.length <= 0) return;
        var opts = {
            allowClear: true,
            disabled: false,
            isSearch: Infinity,
            data: [],
            placeholderId: "",
            placeholderText: "",
            defaultValue: null,
            onChangeCallback: function(){},//Call the function callback after change 
        };
        jQuery.extend(opts, options);
        var select = selector.select2({
            placeholder: {
                id: (opts.placeholderId) ? opts.placeholderId : "",
                text: (opts.placeholderText) ? opts.placeholderText : ''
            },
            data: opts.data,
            allowClear: opts.allowClear,
            minimumResultsForSearch: opts.isSearch,
            disabled: opts.disabled,
            language:{noResults : function () { return ''; }}
        });
        if (typeof opts.onChangeCallback === 'function') {
            select.on('change', function(){
                var id = jQuery(this).val();
                opts.onChangeCallback(id);
            });
        }
        if (opts.defaultValue != null) {
            select.val(opts.defaultValue).trigger('change');
        }
        return select;
    }

    /*
    * Navbar with a side bar when viewed in mobile
    *
    * @return void
    */
    Libs.spMenuToggle = function()
    {
        var selector = jQuery('.navbar-toggler'),
            sidebar = jQuery('.main-menu');
        if(!selector.length || !sidebar.length) return;
        selector.on('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            if (!jQuery('.menu-backdrop').length) {
               jQuery('.header').append('<div class="menu-backdrop">&nbsp;</div>');
            }
            sidebar.animate({
                left: 0
            }, "linear", function(){
                jQuery('.menu-backdrop').on('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    sidebar.animate({
                        left: -leftPosition
                    }, "linear");
                    jQuery('.menu-backdrop').remove();
                });
            });
        });
    }

    /**
    * Open confirn dialog
    *
    * @param array options
    * @return void
    */
    Libs.confirmDialog =  function(options)
    {
        var settings = $.extend({
            title: '',
            message: '',
            cancel: null,//cancel function callback
            ok: null,//ok function callback
            textAlign: ''
        }, options);

        var textAlign = "";
        if(Libs.isBlank(settings.textAlign))
        {
            textAlign = 'text-center';
        }
        else if(settings.textAlign == 'left'){
            textAlign = 'text-left';
        }
        else if(settings.textAlign == 'right'){
            textAlign = 'text-right';
        }
        else
        {
            textAlign = 'text-center';
        }

        bootbox.dialog({
            title: settings.title,
            message: '<div class=\"'+textAlign+'\">'+Libs.nl2br(settings.message)+'</div>',
            closeButton: (settings.title) ? true : false,
            className: "confirm-dialog", 
            buttons:[
                {
                    label: 'キャンセル',
                    className: 'btn-black',
                    callback: settings.cancel
                },
                {
                    label: 'OK',
                    className: 'btn-main',
                    callback: settings.ok
                }
            ]
        });
    }

    /**
    * Open alert dialog
    *
    * @param array options
    * @return void
    */
    Libs.alertDialog = function(options)
    {
        var settings = jQuery.extend({
            title: '',
            message: '',
            ok: null,//ok function callback
            textAlign: ''
        }, options);

        var textAlign = "";
        if(Libs.isBlank(settings.textAlign))
        {
            textAlign = 'text-center';
        }
        else if(settings.textAlign == 'left'){
            textAlign = 'text-left';
        }
        else if(settings.textAlign == 'right'){
            textAlign = 'text-right';
        }
        else
        {
            textAlign = 'text-center';
        }
        bootbox.dialog({
            title: settings.title,
            message: '<div class=\"'+textAlign+'\">'+Libs.nl2br(settings.message)+'</div>',
            closeButton: (settings.title ? true : false),
            className: (settings.title ? "confirm-dialog confirm-dialog-normal" : "confirm-dialog"),
            buttons: {
                ok: {
                    label: 'Ok',
                    className: 'btn-primary',
                    callback: settings.ok
                }
            }
        });
    }

    /**
    * Open confirn dialog
    *
    * @param array options
    * @return void
    */
   Libs.customButtonsDialog =  function(options)
   {
       var settings = $.extend({
           title: '',
           message: '',
           buttons: [],
           textAlign: ''
       }, options);

       var textAlign = "";
       if(Libs.isBlank(settings.textAlign))
       {
           textAlign = 'text-center';
       }
       else if(settings.textAlign == 'left'){
           textAlign = 'text-left';
       }
       else if(settings.textAlign == 'right'){
           textAlign = 'text-right';
       }
       else
       {
           textAlign = 'text-center';
       }
       var buttons = [];
       for(var i = 0; i < settings.buttons.length; i++){
           var button = settings.buttons[i];
           buttons.push({
                label: button.name,
                className: 'btn-primary',
                callback: button.calback
           });
       }
       bootbox.dialog({
           title: settings.title,
           message: '<div class=\"'+textAlign+'\">'+Libs.nl2br(settings.message)+'</div>',
           closeButton: (settings.title) ? true : false,
           className: "confirm-dialog",
           buttons: buttons
       });
   }

    /*
     * show loadding
     *
     */
    Libs.showLoading = function() {
        var loadingHtml = '<div class="fl-loading" style="position: fixed; z-index: 9999999; top: 0; bottom: 0; left: 0; right: 0;">'
                +'<div style="position: absolute; left: 50%; top: 50%; margin-left: -30px; margin-top: -30px;border: 5px solid #f3f3f3; border-radius: 50%;border-top: 5px solid #3498db;width: 60px;height: 60px;-webkit-animation: spin 1s linear infinite;animation: spin 1s linear infinite;"></div>'
                +'<style>@-webkit-keyframes spin {0% { -webkit-transform: rotate(0deg); }100% { -webkit-transform: rotate(360deg); }}@keyframes spin {0% { transform: rotate(0deg); }100% { transform: rotate(360deg);}}</style></div>';
        jQuery('body').append(loadingHtml);
    };

    /*
     * remove loadding
     *
     */
    Libs.removeLoading = function() {
        if(jQuery('.fl-loading').length > 0){
            jQuery('.fl-loading').remove();
        }
    };

    /*
     * Display message required to form
     *
     * @param string|array data
     * @return void
     */
    Libs.formError = function(data, elemForm) {
        if ($('.frm-error').length > 0) {
            $('.frm-error').remove();
        }
        if (!data) {
            return;
        }
        var itemHtml = '';
        if (typeof data === 'string') {
            itemHtml = '<p class="error">※'+data+'<p/>';
        } else {
            for (var i=0; i<data.length; i++) {
                itemHtml += '<p class="error">※'+data[i]+'<p/>';
            }
        }
        var errHtml = '<div class="frm-error">'+itemHtml+'</div>';
        if (Libs.isBlank(elemForm)) {
            $('form').append(errHtml);
        } else {
            $(elemForm).append(errHtml);
        }
    };

    Libs.scrollTop = function()
    {
      var page_top = $('.btn-scroll-top');
      if(!page_top.length) return;
        page_top.hide();
        $(window).scroll(function () {
            if ($(this).scrollTop() > 200) {
                page_top.fadeIn();
            } else {
                page_top.fadeOut();
            }
            page_top.off('click').on('click', function(){
                $('html, body').animate({scrollTop: 0}, 500);
            });
        });
    }

    Libs.setItemHeight = function(ele, isInnerHeight) {
        if (Libs.isBlank(isInnerHeight)) {
            isInnerHeight = false;
        } else {
            isInnerHeight = true;
        }
        var heightArr = [];
        $(ele).css({'height': 'auto'});
        $(ele).each(function() {
            var itemH =  parseInt($( this ).height());
            if (isInnerHeight) {
                itemH = parseInt($( this ).innerHeight());
            }
            heightArr.push(itemH);
        });
        var maxH = Math.max.apply(Math, heightArr);
        $(ele).css({'height':maxH});
    }

    /**
    * Get word count
    *
    * @returns void
    */
    Libs.getWordCount = function(str) {
        if (Libs.isBlank(str)) {
            return;
        }
        //Delete accented characters
        str = Libs.replaceAccents(str);
        str = Libs.strReplaceLineBreaks(str);
        var wordCountNonASCII = 0;
        var wordCountASCII = 0;
        var chrCountASCII = 0;
        //Get none ASCII Characters
        var nonASCIIChar = Libs.getNonASCIIChars(str);
        if (nonASCIIChar) {
            wordCountNonASCII = nonASCIIChar.length;
        }
        //Get none ASCII Characters
        var ASCIIChar = Libs.getASCIIChar(str);
        if (ASCIIChar) {
            ASCIIChar = Libs.strSingleSpace(ASCIIChar,'');
            var txtChr = ASCIIChar.replace(/\s/g, '');
            chrCountASCII = txtChr.length;
            ASCIIChar = ASCIIChar.split(' ');
            wordCountASCII = ASCIIChar.length;
        }
        return {
            word_count: wordCountNonASCII + wordCountASCII,
            chr_count: wordCountNonASCII + chrCountASCII
        };
    }

    /**
    * Build events for html elements
    *
    * @returns void
    */
    Libs.buildEvents = {
        /**
        * Apply events for html elements
        *
        * @param array evs  Array contains the list of events 
        *                   ex: [[element1, {click: function(e){}}], [element2, {mousedown: function(e){}}]]
        * @returns void
        */
        _applyEvents: function(evs){
            for (var i=0, el, ch, ev; i < evs.length; i++) {
                el = evs[i][0];
                if (evs[i].length === 2){
                    ch = undefined;
                    ev = evs[i][1];
                } else if (evs[i].length === 3){
                    ch = evs[i][1];
                    ev = evs[i][2];
                }
                el.on(ev, ch);
            }
        },
        _unapplyEvents: function(evs){
            for (var i=0, el, ev, ch; i < evs.length; i++) {
                el = evs[i][0];
                if (evs[i].length === 2){
                    ch = undefined;
                    ev = evs[i][1];
                } else if (evs[i].length === 3){
                    ch = evs[i][1];
                    ev = evs[i][2];
                }
                el.off(ev, ch);
            }
        },
        _attachEvents: function(evs){
            this._detachEvents(evs);
            this._applyEvents(evs);
        },
        _detachEvents: function(evs){
            this._unapplyEvents(evs);
        }   
    }
    jQuery(document).ready(function(){
        Libs.scrollTop();
    });

    /* Export: */
    return Libs;
}));
