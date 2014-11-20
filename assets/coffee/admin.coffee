$ = jQuery

$ ->
	##
	# Tabs
	##

	$( window ).on 'hashchange', ->
		$( '.tab-wrapper .tab-content' ).removeClass( 'active' )
		$( '.tab-wrapper ' + window.location.hash ).addClass( 'active' )
		$( '.nav-tab-wrapper .nav-tab' ).removeClass( 'nav-tab-active' )
		$( '.nav-tab-wrapper a[href="' + window.location.hash + '"]' ).addClass( 'nav-tab-active' )
		return false;

	if( 0 < window.location.hash.length )
		$( window ).trigger( 'hashchange' )

	##
	# Periods
	##

	$( '.tp-periods table .dashicons-trash' ).click ->
		return confirm( TP_Opening_Hours[ 'periods_trash_confirm' ] );

	##
	# Special date
	##
	#
	$( '.tp-special table .dashicons-trash' ).click ->
		return confirm( TP_Opening_Hours[ 'special_date_trash_confirm' ] );
