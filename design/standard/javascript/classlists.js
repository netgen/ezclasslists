// $Id: classlists.js 18 2011-06-22 19:53:59Z dpobel $
// $HeadURL: http://svn.projects.ez.no/ezclasslists/tags/ezclasslists_1_2/ezclasslists/design/standard/javascript/classlists.js $
// TODO: rewrite this !
var CLASSLISTS_MAIN_CONTENT_ID = 'ezclasslists-main-content';

function classListsFilter( e, options )
{
    var baseURL = options.baseURL;
    var imgLoader = options.loader;
    if ( !YAHOO.util.Connect.asyncRequest )
    {
        return true;
    }
    var callback = { success: function( o )
                              {
                                  var content = document.getElementById( CLASSLISTS_MAIN_CONTENT_ID );
                                  if ( !content )
                                  {
                                      return;
                                  }
                                  content.innerHTML = o.responseText;
                              }
                   };

    classListsWait( imgLoader );

    data = buildPostData();
    var transaction = YAHOO.util.Connect.asyncRequest( 'POST', baseURL, callback, data );

    YAHOO.util.Event.preventDefault( e );
}

function classListsWait( imgLoader )
{
    var content = document.getElementById( CLASSLISTS_MAIN_CONTENT_ID );
    if ( !content )
    {
        return;
    }
    content.innerHTML = '<p style="text-align:center;padding-top:4em;"><img src="' + imgLoader + '" alt="Loading..." /></p>';
}

function buildPostData()
{
    var data = {};

    data.classIdentifier = classListsGetSelectValue( 'classIdentifier' );
    data.sortMethod = classListsGetSelectValue( 'sortMethod' );
    data.sortOrder  = classListsGetSelectValue( 'sortOrder' );

    // @todo: get dates and add them optionally to the url

    var rootNodeId = document.getElementById('rootNodeId').value;
    data.rootNodeId = rootNodeId !== '' ? rootNodeId : 1;

    data.ajax = true;

    var dataString = '';
    $.each(data, function(index, value) {
        dataString += '&'+index+'='+value;
    });

    return dataString.slice(1); // slice is to remove leading '&'
}

function classListsGetSelectValue( selectID )
{
    var element = document.getElementById( selectID );
    if ( !element )
    {
        return '';
    }
    return element.options[element.selectedIndex].value;
}
