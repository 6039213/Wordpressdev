/*
 * Hoofd JavaScriptbestand voor het Restaurant Schoolbord thema.
 *
 * Hier kun je extra interactieve functionaliteiten toevoegen. Standaard hoeft
 * hier geen code te staan omdat de slider met CSS wordt aangestuurd.
 */

// Voorbeeld: dit script voegt een eenvoudige klasse toe aan de header zodra er wordt gescrold.
document.addEventListener( 'DOMContentLoaded', function () {
	var header = document.querySelector( '.site-header' );
	if ( ! header ) return;
	window.addEventListener( 'scroll', function () {
		if ( window.scrollY > 50 ) {
			header.classList.add( 'scrolled' );
		} else {
			header.classList.remove( 'scrolled' );
		}
	} );
} );
