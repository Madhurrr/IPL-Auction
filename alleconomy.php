<?php
 //Connect to mysql server
  $link = mysqli_connect('localhost' , 'root', '');
 //Check link to the mysql server
 if(! $link){
 die('Failed to connect to server: ' . mysqli_error());
 }
 //Select database
 $db = mysqli_select_db($link, 'ipl');
 if(! $db){
 die("Unable to select database");
 }

 //Prepare query
 $query = "select D.id,D.player_name,runs/overs as economy from (select
id,player_name,count(overno)as overs from bowler inner join players where player_id=id group by
player_id) as D, (select E.id,E.player_name,runs1+runs2 as runs from (select id,player_name,sum(runs)
as runs1 from batsman inner join bowler inner join players where batsman.match_id=bowler.match_id
and batsman.year=bowler.year and batsman.inning=bowler.inning and batsman.overno=bowler.overno
and bowler.player_id=id group by bowler.player_id)as E, (select id,player_name,sum(runs) as runs2 from
extras inner join bowler inner join players where extras.match_id=bowler.match_id and
extras.year=bowler.year and extras.inning=bowler.inning and extras.overno=bowler.overno and
bowler.player_id=id group by bowler.player_id)as F where E.id=F.id ) as C where D.id=C.id order by
economy ";
 //Execute query
 $result = mysqli_query($link,$query);
echo "<center>";
 echo "<table border='1' cellpadding = '10'>
 <tr><th>Position</th>
 <th> Player Name</th>
 <th>Economy</th>
 <th>Team</th>
 </tr>";
 "<br>";
$i=1;
 //Show the rows in the fetched resultset one by one
 while ($row =mysqli_fetch_assoc($result))
 {
	 $pl=$row[ 'player_name' ];
	 $que="select t.name
from team t
inner join playsfor p
on p.team_id=t.id 
inner join players pl
on pl.id=p.player_id and pl.player_name='$pl'";
 $r = mysqli_query($link,$que);
 $ro =mysqli_fetch_assoc($r);
 echo '<tr>
 <td align="center" >' .$i. '</td>
 <td align="center">' . $row[ 'player_name' ]. '</td>
 <td align="center">' . $row[ 'economy' ]. '</td>
<td align="center">' .$ro['name']. '</td>
 </tr>';
	$i++;
 }
 echo '</table>' ; 
 


 
?>