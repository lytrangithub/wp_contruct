(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
  typeof define === 'function' && define.amd ? define(factory) :
  (global = global || self, global.card = factory());
}(this, function () {
  	'use strict';
  	var CGlobal = {
        elemSelectCard: '#select-card',
        elemSelectLang: '#select-lang',
        elemCardItemInner: '.image-list .item-inner',
        elemCardDetailItemInner: '.page-detail .image-list .item-inner',
        elemCardIem: '.coachcard-content .item',
        elemCoachCardContent: '.coachcard-content',
        elemChooseCard: '#choose-card',
        currentLang: '',
        numberCard: 1,
        isPageLoad: false
  	};
    var STORAGE_KEY = {
        LANG: 'coachcard_lang',
        NUMBER_CARD: 'coachcard_number_card'
    };
	var card = {
		
        /**
         * Set data when page load
         *
         * @return void
         */
        onPageLoad: function(currentLang) {
            // localStorage.removeItem(STORAGE_KEY.LANG);
            // return;
            var langStorage = localStorage.getItem(STORAGE_KEY.LANG);
            var numberCardStorage = localStorage.getItem(STORAGE_KEY.NUMBER_CARD);
            if (Libs.isBlank(langStorage)) {
                langStorage = currentLang;
                localStorage.setItem(STORAGE_KEY.LANG, langStorage);
            }
            if (Libs.isBlank(numberCardStorage)) {
                numberCardStorage = 1;
                localStorage.setItem(STORAGE_KEY.NUMBER_CARD, numberCardStorage);        
            }
            CGlobal.currentLang = langStorage;
            CGlobal.numberCard = numberCardStorage;
            this.getCoachCardByLang();
        },

        /**
         * Get coach card by lang
         *
         * @return void
         */
        getCoachCardByLang: function(){
            var url = ajaxUrl+'coachcard_list_by_lang';
            var params = 'lang='+CGlobal.currentLang + '&number_card='+CGlobal.numberCard;
            FLHttp.post(url, '', params).success(function(res){
                var coachcard_detail_html = Libs.decodeHTMLEntities(res.data);
                $(CGlobal.elemCoachCardContent).empty().html(coachcard_detail_html).ready(function(){
                    setTimeout(function(){
                        Libs.setContentHeight(CGlobal.elemCardItemInner, true);
                    }, 250);
                    cardEvents._buildEvents();
                });
                CGlobal.isPageLoad = true;
            });
        },

        /**
         * Card change
         *
         * @return void
         */
        cardChange: function(){
            Libs.selectDropdown(CGlobal.elemSelectCard, {
                allowClear: false,
                defaultValue: CGlobal.numberCard*1,
                onChangeCallback: function(id){
                    CGlobal.numberCard = id;
                    if (CGlobal.isPageLoad) {
                        localStorage.setItem(STORAGE_KEY.NUMBER_CARD, id);
                        card.getCoachCardByLang();
                    }
                }
            });
        },

        /**
         * Language change
         *
         * @return void
         */
        langChange: function(){
            Libs.selectDropdown(CGlobal.elemSelectLang, {
                allowClear: false,
                defaultValue: CGlobal.currentLang,
                onChangeCallback: function(id){
                    CGlobal.currentLang = id;
                    if (CGlobal.isPageLoad) {
                        localStorage.setItem(STORAGE_KEY.LANG, id);
                        card.getCoachCardByLang();
                    }
                }
            });
        },

        /**
         * Choose card by lang and number of cards
         *
         * @return void
         */
        onChooseCard: function(){
            localStorage.setItem(STORAGE_KEY.NUMBER_CARD, CGlobal.numberCard);
            localStorage.setItem(STORAGE_KEY.LANG, CGlobal.currentLang);
            card.getCoachCardByLang();
        },

        /**
         * Open card detail
         *
         * @return void
         */
        openCardDetail: function(){
            var own = $(this);
            var id = $(this).attr('data-id');
            if(Libs.isBlank(id)) {
                return;
            }
            var url = ajaxUrl+'coachcard_detail';
            var params = 'id='+id;
            FLHttp.post(url, '', params).success(function(res){
                if (!res.status) {
                    return;
                }
                var keyword = res.data.keyword;
                var cardDetail = res.data.card_detail;
                var coachcard_detail_html = Libs.decodeHTMLEntities(cardDetail);
                var modal = Libs.openModal(null, coachcard_detail_html);
                //Set card item height
                setTimeout(function(){
                    Libs.setContentHeight(CGlobal.elemCardDetailItemInner, true);
                }, 250);
            });
        },
	}

	/**
     * Define events for all screens
     *
     * @return void
     */
    var cardEvents = {

        /**
         * Build events according to the screen
         *
         * @return void
         */
        _buildEvents: function(){
            var evs = [
                [$(CGlobal.elemChooseCard), {click: card.onChooseCard}],
                [$(CGlobal.elemCardIem), {click: card.openCardDetail}]
            ];
            Libs.buildEvents._attachEvents(evs);
        }
    }

    $(document).ready(function(){
        //Select card
        card.cardChange();
        //Select language
        card.langChange();
    });

    /* Export: */
    return card;
}));
