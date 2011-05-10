<?php 
			session_start();
			require_once 'lib/db/sno_db_interface.php';
			require 'bitly/gChart.php';
			require 'bitly/sno_bitly.php';
			require_once 'lib/functions.php';
			require_once 'lib/access.class.php';

			$user2 = new flexibleAccess();
			
			$user = $user2->userID;
 
			/* Posts associated by Network Id
			 *  SELECT * FROM networks
				LEFT JOIN posts ON networks.network_id = posts.network_id
				WHERE networks.user_id = 8
				ORDER BY posts.bitly_link

			 */
			$mapQuery = "SELECT DISTINCT `feed_url` FROM `maps` 
			                    LEFT JOIN `networks` on networks.network_id = maps.network_id 
			                    WHERE networks.user_id=?";
			$pdoStatement = sno_db_interface::executePreparedQueryN($mapQuery, array($user));
			$mapResults = $pdoStatement->fetchAll();
				
			$query = "SELECT * FROM networks
						LEFT JOIN posts ON networks.network_id = posts.network_id
						WHERE networks.user_id = 8
						ORDER BY posts.bitly_link";
			$pdoStatement = sno_db_interface::executePreparedQueryN($query,array($user));
			$result = $pdoStatement->fetchAll();			
			
			function makeGraph($url){	
				$bitly = new Bitly();
				$clicks = $bitly->clicksUrl($url);
				$referrer = $bitly->referrerUrl($url);
				$chart_labels = array();
				$chart_data = array();
				$i = 0;
				foreach($referrer as $ref){
					$chart_labels[$i]=$ref['referrer'];
					$chart_data[$i]=$ref['clicks'];
					$i++;
				}
		
				$piChart = new gPieChart(250,275);
				$piChart->setProperty('chdls', 'FFFFFF,12',false);
				$piChart->setProperty('chdlp', 'b',false);
				$piChart->addDataSet($chart_data);
				$piChart->setLegend($chart_labels);
				$piChart->addBackgroundFill('bg', '00000000');
				?>
				<table border=0 width="100%"><tr><td>
				<?php 
				echo "<div float=\"left\"><img src=\"".$piChart->getUrl()."\"/></div>";
				?>
				</td>
				<td width=200>
				<div>
					<table rules="cols" align="center" style="color: #FFFFFF; font-size:12;">
						<tr style="border-bottom: 2px solid black;">
							<th colspan=2>Clicks Breakdown</th>
						</tr>
						<?php 
							foreach($referrer as $ref){
								echo "<tr><td>".$ref['referrer']."</td><td align=\"right\">".$ref['clicks']."</td></tr>";
							}					
						?>
					</table>
				</div>
				</td></tr>
				<tr colspan=2 style="font-size:12;">
					<td><p><br>Bitly URL: <a href="<?php echo $url;?>"><font color="#FFFFFF"><?php echo $url;?></font></a>
					<br>Any more info?</p></td>
				</tr>
				</table>

				<?php 
			}
			
		
	?>
		<script type="text/javascript">
		/*
		ajax.php Option list
		0 : New Feed [Fabio]
		1 : Toggle Networkid / Feed active status [Tom] 
		*/
			function initMenu(){
				$('.feed_details').hide();
				$('.feed').click(
						function(){
							$(this).next().slideToggle(100);

						});
			}

			$("#toggle .feed_details p#net_toggle").click(function(){
				var obj = $(this);
				$.get("ajax.php",{ 'option' : 1, 'values': $(this).attr("value"),'state': $(this).attr("state")}, function(data){
					obj.attr("state", !obj.attr('state'));
 					if(data){
						obj.toggleClass("toggle_active toggle_inactive");
					} else {
						obj.toggleClass("toggle_inactive toggle_active");
					}
					//alert(obj.attr("value"));		

					
				});
			});
								
			function refresh (){
				$.get("feed_info.php", { 'user': <?php echo "'".base64_encode(json_encode($user))."'";?> }, function(data){
					$('#infobox').html(data);
				});
			}
			$(document).ready(function(){initMenu();});
 		</script>
 	
		<div id="feed_list_id" class="feed_list">
			<div id="refresh" onclick="refresh()" align="right"><img src="http://traduccionmasinterpretacion.com/img/refresh.png" width="40px"></div>
		<?php 
		if(!$result)
		{
				echo "<p style=\"color: #FFFFFF; font-size:18; weight: bold;\">No Feeds to Display.</p>";
		}
		?>
		<div id="toggle">
		
		
<?php 
		
		foreach($mapResults as $map){
			
			// Get Posts pertaining to MAP URL
			$query = "SELECT * FROM `posts` WHERE feed_url = ?";
			$pdoStatement = sno_db_interface::executePreparedQueryN($query,array($map[0]));
			$posts = $pdoStatement->fetchAll();
			
			
			// Get Networks Associated with User
			$query = "SELECT * FROM networks WHERE user_id = ?";
			$pdoStatement = sno_db_interface::executePreparedQueryN($query,array($user));
			$networks = $pdoStatement->fetchAll();

			$parse = parse_url($map[0]);
		
			?>
			<p class="feed" align="left"><a href="<?php echo fixURL($parse['host']);?>"><?php echo $parse['host'];?></a>
				<div class="feed_details">
				<ul id="networks">
				<?php 
					foreach($networks as $network){
						
						// Find active Maps
						$query = "SELECT active_state FROM maps WHERE network_id = ? AND feed_url = ?";
						$pdoStatement = sno_db_interface::executePreparedQueryN($query,array($network['network_id'],$map['feed_url']));
						$active_state = $pdoStatement->fetchAll();
				?>
					<li><p id="net_toggle" class="<?php echo ($active_state[0]['active_state'] ==1) ? 'toggle_active' : 'toggle_inactive'; ?>"  value="<?php echo base64_encode(json_encode(array( $map['feed_url'], $network['network_id'])));?>"  state="<?php echo $active_state[0]['active_state'];?>" >
						<?php echo $network['network_name']. " - ". $network['network_label'];?>
					</p></li>
				
				<?php 
					}
					echo "</ul>";
					if(!empty($posts))
						foreach($posts as $post){
							makeGraph($post["bitly_link"]);
						}
					else
						echo "Sorry no posts have been Found."; 
				?>
				
				</div>
				
				
				
			</p>
			
			
			
			<?php
		}
?>

		</div>
			
			
		
	<div id="resultFeed"></div>