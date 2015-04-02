<div style="float:top; margin:0px; padding:0px">
  <div style="float:left; margin:4px">
    <form name="driftButton" action="drift.php" method="post" style="margin:0px; padding:0px;">
      <input type="submit" name="driftButton" value="Drift!" />
    </form>
  </div>
  <?php
    if( $users->is_authorized() )
    {
      echo( '
      <div style="margin:4px; margin-left:0px; float:left;">
        <form style="margin:0px; padding:0px; display:inline;">
          <input type="button" id="likeButton"    value="Like"    onClick="driftBarClick( ' . $_GET["id"] . ', \'like\' )" />
          <input type="button" id="dislikeButton" value="Dislike" onClick="driftBarClick( ' . $_GET["id"] . ', \'dislike\' )" />
          <input type="button" id="deadButton"    value="Expired" onClick="driftBarClick( ' . $_GET["id"] . ', \'dead\' )" />
        </form>
        <p style="display:inline;">  |  </p>
        <form name="newUrlForm" action="http://dealdrift.com/newdrift.php" method="post" style="margin:0px; padding:0px; display:inline;">
          <input type="text" name="newUrl" value="Add a Deal" onclick="if( this.value==\'Add a Deal\' ) this.value=\'\';" onblur="if( this.value==\'\' ) this.value=\'Add a Deal\';" />
          <input type="text" name="newUrlTags" value="Tags (comma seperated)" onclick="if( this.value==\'Tags (comma seperated)\' ) this.value=\'\';" onblur="if( this.value==\'\' ) this.value=\'Tags (comma seperated)\';" />
          <input type="submit" name="newUrlButton" value="Add" />
        </form>
      </div>
      <div style="margin:4px; float:right;">
        <a href="http://dealdrift.com/settings.php">settings</a>  |  <a href="http://dealdrift.com/login.php?logout=1">logout</a>
      </div>' );
    }
    else
    {
      echo( '
      <div style="margin:4px; float:right;">
        <a href="http://dealdrift.com/login.php">login</a>  |  <a href="http://dealdrift.com/register.php">sign up</a>
      </div>' );
    }
  ?>
</div>
<div id="outerSeparator">
  <div id="separator"></div>
</div>
