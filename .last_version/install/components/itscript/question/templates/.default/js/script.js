$(document).ready(function(){

    $.fn.serializeObject = function(){

        var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push":     /^$/,
                "fixed":    /^\d+$/,
                "named":    /^[a-zA-Z0-9_]+$/
            };


        this.build = function(base, key, value){
            base[key] = value;
            return base;
        };

        this.push_counter = function(key){
            if(push_counters[key] === undefined){
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };

        $.each($(this).serializeArray(), function(){

            // Skip invalid keys
            if(!patterns.validate.test(this.name)){
                return;
            }

            var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

            while((k = keys.pop()) !== undefined){

                // Adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                // Push
                if(k.match(patterns.push)){
                    merge = self.build([], self.push_counter(reverse_key), merge);
                }

                // Fixed
                else if(k.match(patterns.fixed)){
                    merge = self.build([], k, merge);
                }

                // Named
                else if(k.match(patterns.named)){
                    merge = self.build({}, k, merge);
                }
            }

            json = $.extend(true, json, merge);
        });

        return json;
    };

    $('#question-add-btn-js').click(function(){
        $(this).hide();
        $('.question-form').show();
    });

    $('#question-form-btn-js').click(function(){

        let filedsObject = $("#question-form-js").serializeObject();
        filedsObject.URL = document.location.href;

        BX.ajax.runAction('itscript:question.Item.add', {
            data: {
                fields: filedsObject
            }
        }).then(function (response) {
            console.log(response);

            window.location.reload();

            /**
            {
                "status": "success", 
                "data": {
                    "ID": 1,
                    "NAME": "test"
                }, 
                "errors": []
            }
            **/			
        }, function (response) {
            //сюда будут приходить все ответы, у которых status !== 'success'
            console.log(response);
            /**
            {
                "status": "error", 
                "errors": [...]
            }
            **/				
        });
    });
});