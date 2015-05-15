jQuery.fn.extend({
	getPath: function( path ) {
		// The first time this function is called, path won't be defined.
		if ( typeof path == 'undefined' ) path = [];
		
		// If this element is <body> we've reached the end of the path.
		if ( this.is('html') ){
			//path.push('body');
			return path;
		}
        var id = this.attr('id'),
            c = this.attr('class');
        if(typeof c != 'undefined' && c != '' && c.search(/block-wrapper/i) > -1) {
            return this.parent().getPath( path );
        }
		// Add the element name.
		//console.debug(this, this.get(0));
		var cur = this.get(0).nodeName.toLowerCase();
		
		// Determine the IDs and path.

		// Add the #id if there is one.
		/* - */ /*if ( typeof id != 'undefined')*/
		/* + */ if ( typeof id != 'undefined' && id != '' ) // <- FGRibreau fix
			cur += '#' + id;
		
		// Add any classes.
		/* - */ /*if ( typeof class != 'undefined' )*/
		/* + */ if ( typeof c != 'undefined' && c != '' ){ // <- FGRibreau fix
			cur += '.' + c.split(/[\s\n]+/).join('.');
			cur = cur.replace('.active-selector', '');
//			cur = cur.replace('.block-wrapper', '');
			cur = cur.replace('..', '.');
		}
		
		path.push( cur );
		// Recurse up the DOM.
		return this.parent().getPath( path );
	}
});