<div style="max-width:800px;">
<p>This program allows to build a visual representation of the <a href="https://en.wikipedia.org/wiki/Six_degrees_of_separation" target="_blank">popular theory</a> that everyone is six or fewer handshakes away from any other person in the world. 
For the predefined social graph of users, the program automatically builds "a friend of a friend" chain which connects any two users in a maximum of six steps, using the shortest path. 
</p>
<p>
<form action="index.php" method="POST">
<label>Connect from user ID:</label> <input type="text" name="ufrom" value="<?php echo $ufrom;?>" />
<label>To user ID:</label> <input type="text" name="uto" value="<?php echo $uto;?>" />
<input type="submit" value="Go!" />
<input type="hidden" name="task" value="build" />
</form>
</p>
<p>
<form action="index.php" method="POST">
<input type="hidden" name="task" value="generate" />
<input type="button" value="Rebuild the graph" />
</form>
</p>
<p>For this demo, you can refer to the following randomly generated directed graph of user IDs ("social graph"):
<img src="images/social_graph.png" width="700"/>
</p>
<p>
You can, of course, generate your own social graph and use the program to visualize connection chains. 
</p>
</div>