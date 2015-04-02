var xmlhttp

function driftBarClick( id, action )
{
  // alert( "_driftBarClick( " + id + ", " + action + " )" );
  xmlhttp = getXmlHttpObject();
  if( xmlhttp == null )
  {
    alert( "Your browser does not support AJAX" );
    return;
  }
  var url = "http://dealdrift.com/drift.php?id=" + id + "&action=" + action;
  xmlhttp.open( "GET", url, true );
  xmlhttp.onreadystatechange = stateChanged;
  xmlhttp.send( null );
}

function stateChanged()
{
  if( xmlhttp.readyState == 4 )
  {
    if( xmlhttp.responseText == "like" )
    {
      document.getElementById( "likeButton" ).style.backgroundColor = "green";
    }
    else if( xmlhttp.responseText == "dislike" )
    {
      document.getElementById( "dislikeButton" ).style.backgroundColor = "red";
    }
    else if( xmlhttp.responseText == "dead" )
    {
      document.getElementById( "deadButton" ).style.backgroundColor = "grey";
    }
    else  // Error. Should never get here.
    {
      // alert( "Error: Problem in function stateChanged()" );  // This happened once for something unrelated when nothing would have gone wrong. Don't do anything if we get here.
    }
  }
}

function getXmlHttpObject()
{
  if( window.XMLHttpRequest )
  {
    // Code for IE7+, Firefox, Chrome, Opera, Safari.
    return new XMLHttpRequest();
  }
  if( window.ActiveXObject )
  {
    // Code for IE6, IE5.
    return new ActiveXObject( "Microsoft.XMLHTTP" );
  }
  return null;
}
