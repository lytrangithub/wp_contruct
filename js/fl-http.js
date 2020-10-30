(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
  typeof define === 'function' && define.amd ? define(factory) :
  (global = global || self, global.FLHttp = factory());
}(this, function () {
    'use strict';
    var FLHttp = {};
    var FLHttpConfig = {

        /**
        * Initialize ajax request
        *
        * @param {string} url
        * @param {string} formid
        * @param {string} params   query string: "param1=1&param2=test"
        * @param {string} type   method get or post
        * @param {string} dataType   The type of data expected to be returned from the server
        * @param isProgressShow   true: display loading, false: Do not show loading
        * @returns {object} promise
        */
        initialize: function(url, formid, params, type, dataType, isProgressShow){
            var parameters = Libs.getParamString(formid);
            if (!Libs.isBlank(params))
                parameters += (parameters ? '&' : '') + params;
            var deferred = jQuery.Deferred();
            var promise = deferred.promise();
            promise.success = function (fn) {
                promise.then(function (data) {
                    fn(data);
                });
                return promise;
            };
            promise.error = function (fn) {
                promise.then(null, function (status, err) {
                    fn(status, err);
                });
                return promise;
            };
            if (Libs.isBlank(isProgressShow) || isProgressShow)
            {
                isProgressShow = true;
                jQuery('body').append(this.showLoading());
            }
            if (Libs.isBlank(dataType))
            {
              dataType = "json";
            }
            if (Libs.isBlank(type))
            {
                type = "POST";
            }
            // if (params && typeof params === 'string') {
            //     var fd = new FormData();
            //     var objParams = JSON.parse('{"' + params.replace(/&/g, '","').replace(/=/g,'":"') + '"}', function(key, value) { return key===""?value:decodeURIComponent(value) });
            //     console.log(objParams);
            //     jQuery.each(objParams, function (k, v) {
            //         var value = (typeof v === 'string') ? Libs.encodeURL(v) : v;
            //         fd.append(k, value);
            //     });
            // }
            jQuery.ajax({
                url: url,
                type: type,
                dataType: dataType,
                data: parameters,
                //timeout: 30000, //30 second timeout
                headers: FLHttpConfig.setHeader(),
                success: function (res, textStatus, jqXHR) {
                  if (isProgressShow) {
                      FLHttpConfig.removeLoading();
                  }
                  deferred.resolve(res);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //console.log('Http error: '+url, jqXHR.status, '-'+textStatus, '-'+errorThrown);
                    if (isProgressShow) {
                        FLHttpConfig.removeLoading();
                    }
                    deferred.reject(jqXHR.status, errorThrown);
                    FLHttpConfig.showHttpErrorMessage(jqXHR.status);
                }
            });
            return promise;
        },

        /**
        * Set header request
        *
        * @returns void
        */
        setHeader: function(type)
        {
            var header = {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            };
            return header;
        },

        /**
        * Create loading append to body
        *
        * @returns void
        */
        showLoading: function(){
            var loadingHtml = '<div class="fl-loading" style="position: fixed; z-index: 9999999; top: 0; bottom: 0; left: 0; right: 0;">'
                +'<div style="position: absolute; left: 50%; top: 50%; margin-left: -30px; margin-top: -30px;border: 5px solid #f3f3f3; border-radius: 50%;border-top: 5px solid #1d498b;width: 60px;height: 60px;-webkit-animation: spin 1s linear infinite;animation: spin 1s linear infinite;"></div>'
                +'<style>@-webkit-keyframes spin {0% { -webkit-transform: rotate(0deg); }100% { -webkit-transform: rotate(360deg); }}@keyframes spin {0% { transform: rotate(0deg); }100% { transform: rotate(360deg);}}</style></div>';
            jQuery('body').append(loadingHtml);
        },

        /**
        * Delete loading if available in body
        *
        * @returns void
        */
        removeLoading: function(){
            if(jQuery('.fl-loading').length > 0){
                jQuery('.fl-loading').remove();   
            }
        },

        /**
        * Get message by status code
        *
        * @param {int} code Error code is returned when calling http request
        * @return {string} Error message
        */
        getHttpErrorMessage: function(code){
            var msg = "";
            switch (code) {
                case 201:
                    msg = "Request is accepted but the process is incomplete.";
                    break;
                case 203:
                    msg = "Non-authoritative Information";
                    break;
                case 204:
                    msg = "No Content: No response from server.";
                    break;
                case 205:
                    msg = "Reset Content: Your request is processed successfully but no response from server. In contrast to 204 No Content Response, it requires clients to reset the document view. ";
                    break;
                case 206:
                    msg = "Partial Content: Server returned an incomplete result due to internet connection or an error occurred while downloading.";
                    break;
                case 404:
                    msg = "Page not found";
                    break;
                case 419:
                    msg = "Sorry, your session has expired. Please refresh and try again";
                    break;
                case 500:
                    msg = "HTTP Version Not Supported";
                    break;
                case 501:
                    msg = "Not Implemented";
                    break;
                case 502:
                    msg = "Bad Gateway";
                    break;
                case 503:
                    msg = "Service Unavailable";
                    break;
                case 504:
                    msg = "Gateway Timeout";
                    break;
                case 505:
                    msg = "HTTP Version Not Supported";
                    break;
                default: msg = code;
                    break;
            }
            return msg;
        },

        /**
         * Add text error message when not calling server or api error
         *
         * @param {int} code Error code is returned when calling http request
         * @return void
         */
        showHttpErrorMessage: function(code){
            var msgErr = this.getHttpErrorMessage(code);
            if(Libs.isBlank(msgErr)) return;
            var html = '<div class="notification-error">';
            html += '<p>';
            html += msgErr;
            html += '<a class="close-notification-error fa fa-times">&nbsp;</a>';
            html += '</p>'
            html += '</div>';
            if (!jQuery('.notification-error').length) {
                jQuery('body').prepend(html);
                this.errorMessageStyle();
            }
            if (jQuery('.close-notification-error').length) {
                jQuery('.close-notification-error').on('click', function () {
                    if (jQuery('.notification-error').length) {
                        jQuery('.notification-error').remove();
                    }
                });
            }
            setTimeout(function () {
                if (jQuery('.notification-error').length) {
                    jQuery('.notification-error').remove();
                }
            }, 5000);
        },

        /**
         * Set style for error message
         *
         * @return void
         */
        errorMessageStyle: function(){
            var notifiError = jQuery('.notification-error');
            if(notifiError.length <= 0) return;
            notifiError.css({
                position: 'fixed',
                left: 0,
                right: 0,
                top: 0,
                zIndex: 999999,
                background: '#fff',
                borderBottom: '1px solid #ddd',
                textAlign: 'center',
                padding: '15px'
            });
            notifiError.find('p').css({
                color: '#ff0000',
                paddingRight: '50px'
            });
            notifiError.find('a').css({
                position: 'absolute',
                right: 0,
                top: 0,
                bottom: 0,
                width: '40px',
                textAlign: 'center',
                display: 'flex',
                alignItems: 'center',
                color: '#ff0000'
            });
        }
    };
    FLHttp.post = function (url, formid, params, dataType, isProgressShow) {
        return FLHttpConfig.initialize(url, formid, params, "POST", dataType, isProgressShow);
    };
    FLHttp.get = function (url, formid, params, dataType, isProgressShow) {
        return FLHttpConfig.initialize(url, formid, params, "GET", dataType, isProgressShow);
    };
    /* Export: */
    return FLHttp;
}));