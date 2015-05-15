WysiwygWidget.Widget.prototype.insertWidget = function() {
    widgetOptionsForm = new varienForm(this.formEl);
    if(widgetOptionsForm.validator && widgetOptionsForm.validator.validate() || !widgetOptionsForm.validator){
        var formElements = [];
        var i            = 0;
        var columnId     = '';
        var widgetId     = '';
        var blockAction  = "widget";

        Form.getElements($(this.formEl)).each(function(e) {
            if(!e.hasClassName('skip-submit')) {
                formElements[i] = e;
                i++;
            }

            switch (e.name) {
                case "widget_type":
                    if (0 == e.value.indexOf("restore:")) {
                        widgetId    = e.value.replace("restore:", "");
                        blockAction = "add";
                    }
                    else {
                        widgetId = e.value.replace("/", "_") + '.' + (new Date).getTime();
                    }
                    break;

                case "themetuner_column":
                    columnId = e.value.split('::').pop();
                    break;
            }
        });

        // Add as_is flag to parameters if wysiwyg editor doesn't exist
        var params = Form.serializeElements(formElements);

        try {
            if (widgetId && columnId) {
                widgetTools.logLayoutChanges({
                    "type"   : "layout", 
                    "action" : blockAction, 
                    "after"  : "-", 
                    "parent" : columnId, 
                    "params" : params, 
                    "id"     : widgetId
                }, true);
            }

            Windows.close("widget_window");
        } catch(e) {
            alert(e.message);
        }
    }
}
