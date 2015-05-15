/**
 * Object that holds currently active HTML elements data.
 */
var ActiveObject = function(iframe, inlineElements, deviceTypes ) {
    this._iframe = iframe;
    this._inlineElements = inlineElements;
	this._deviceTypes = deviceTypes || []; 
    this._selector = ''; // only currently selected selector
    this._selectorTree = []; // complete selector tree
    
    /**
     * Selector modifier functions
     */
    this._modifiers = {
        'withoutPseudoClass': function(selectorParts) {
            var removePseudoClasses = function(selectorPart) {
                jQuery.each(['hover', 'active', 'visited'], function(indx, pseudoClass) {
                    selectorPart = selectorPart.replace('.themetuner-pseudo--' + pseudoClass, '');
                });
                return selectorPart;
            };
            
            for (var i = 0; i < selectorParts.length; i++) {
                selectorParts[i] = removePseudoClasses(selectorParts[i]);
            }
            return selectorParts;
        },
        'oneClassOnly': function(selectorParts){
            for (var i = 0; i < selectorParts.length; i++) {
                if(selectorParts[i].split('.').length > 2) {
                    selectorParts[i] = selectorParts[i].split('.', 2).join('.');
                }
            }
            return selectorParts;
        }
    };
	
	this.setDevices = function( devices ){
		if(!this._deviceTypes.length){
			var types = [], len = devices.length;
			while(len--){
				if( devices[len].type && devices[len].type.length ){
					types.push(devices[len].type);
				}
			}
			return this._deviceTypes = types;		
		}
		return false;
	}
    
    /**
     * If editing pseudo-class, replace selector with custom themetuner pseudo-class and attach it to
     * specified HTML elements, so that user can visually see what he/she is changing.
     */
    this._processPseudoClass = function(selector, pseudoClass) {
        var replacementClass = 'themetuner-pseudo--' + pseudoClass;
        jQuery('*', this._iframe.contentWindow.document).removeClass(replacementClass);
        
        if (selector.indexOf(':' + pseudoClass) != -1) {
            selector = selector.replace(':' + pseudoClass, '.' + replacementClass, 'g');
            
            var offset = 0;
            var occurence;
            do {
                occurence = selector.indexOf('.' + replacementClass, offset);
                if (occurence != -1) {
                    var selectorPart = selector.slice(0, occurence);
                    offset = occurence + replacementClass.length + 1;
                    jQuery(selectorPart, this._iframe.contentDocument).addClass(replacementClass);
                }
            } while (occurence != -1);
        }
        return selector;
    };
    
    this.reset = function() {
        this._selector = '';
        this._selectorTree = [];
        this.clearMarkings();
    };
    
    this.setSelector = function(selector, tree, modifiers) {
        var i;
        var tmpArray = [];
        
        if (!selector && tree) {
            // extract selector from selector tree
            for (i = 0; i < tree.length; i++) {
                var tagName = tree[i].split('.')[0].split('#')[0];
				
				
				// for body tag we include device className
				if (tagName === 'body') {
					var c = tree[i].split('.');
					var deviceClass = '';
					
					for(var v=0, l=c.length; v<l; v++){
						if(jQuery.inArray(c[v], this._deviceTypes) > -1){
							deviceClass = '.' + c[v];
							break;
						}
					}
					
					tmpArray.push(tree[i].split('.')[0] + deviceClass); 
				
					
				} else if (jQuery.inArray(tagName, this._inlineElements) > -1) {
                    tmpArray.push(tree[i].split('.')[0]); // in case of inline element, add only tag with id (without class names)
                } else {
                    tmpArray.push(tree[i]); // otherwise add whole selector part
                }
            }
        } else {
            tmpArray = selector.split(' ');
        }
        
        if (modifiers && modifiers.length) {
            // execute selector modifiers
            for (i = 0; i < modifiers.length; i++) {
                if (typeof modifiers[i] == 'function') {
                    tmpArray = modifiers[i](tmpArray);
                } else if (typeof modifiers[i] == 'string' && this._modifiers[modifiers[i]]) {
                    tmpArray = this._modifiers[modifiers[i]](tmpArray);
                }
            }
        }
        
        selector = tmpArray.join(' ');
        
        var self = this;
        jQuery.each(['hover', 'active', 'visited'], function(indx, pseudoClass) {
            selector = self._processPseudoClass(selector, pseudoClass);
        });
        
        this._selector = selector;
        if (tree) {
            this._selectorTree = tree;
        }
    };
    
    this.getSelector =  function() {
        return this._selector;
    };
    
    this.getSelectorParts = function() {
        var selectorStructure = this.extractSelectorStructure();
        var parts = jQuery.map(selectorStructure, function(itm) {
            return itm.part;
        });
        return parts;
        
    };
    
    /**
     * Get detailed data of selector parts and part components.
     * Selector parameter can be whether array with selector parst or string with complete css selector.
     *
     * For example if "selector" has value "div#some-id.some-class:hover span.test-class",
     * return value would be:
     * [
     *   {'part': 'div#some-id.some-class:hover', 'components', ['div', '#some-id', '.some-class', ':hover']},
     *   {'part': 'span.test-class', 'components', ['span', '.test-class']}
     * ]
     */
    this.extractSelectorStructure = function(selector) {
        
        var sel = selector || this._selector;
        var result = [];
        
        var parts = jQuery.isArray(sel) ? sel : sel.split(' ');
        
        for (var i = 0; i < parts.length; i++) {
            
            var partComponents = parts[i].split('.');
            for (var j = 0; j < partComponents.length; j++) {
                if (j !== 0) {
                    partComponents[j] = '.' + partComponents[j];
                }
            }
        
            // inject id part of selector to second array element
            if (partComponents[0].indexOf('#') != -1) {
                partComponents.splice(0, 1, partComponents[0].split('#')[0], '#' + partComponents[0].split('#')[1]);
            }
            
            // inject pseudo-class part of selector to last array element
            var lastIndex = partComponents.length - 1;
            if (partComponents[lastIndex].indexOf(':') != -1) {
                partComponents.splice(lastIndex, 1, partComponents[lastIndex].split(':')[0], ':' + partComponents[lastIndex].split(':')[1]);
            }
            
            result.push({
                'part': parts[i],
                'components': partComponents
            });
        }
        return result;
    };
    
    this.getElements = function() {
        if (this._selector) {
            var elements = jQuery(this._selector, this._iframe.contentDocument);
            return elements;
        }
        return [];
    };
    
    this.getSelectorTree = function() {
        return this._selectorTree;
    };
    
    this.markElements = function() {
        this.clearMarkings();
        this.getElements().addClass('active-selector');
    };

    this.clearMarkings = function() {
        jQuery('*', this._iframe.contentWindow.document).removeClass('active-selector');
    };
};


// this is needed for JSHINT validation
// to avoid "variable is defined but never used" error
// this hack is only needed in class files
if (1 == 2) {
    new ActiveObject();
}