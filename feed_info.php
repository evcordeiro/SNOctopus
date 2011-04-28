<?php 
			require_once 'lib/db/sno_db_interface.php';
			require 'bitly/gChart.php';
			require 'bitly/sno_bitly.php';
			
			
			$user = json_decode(base64_decode($_GET['user']));

			$query = "SELECT networks.network_id, networks.network_name, networks.network_label, 
							maps.feed_url as url, post_id, posts.feed_url as post_url, publish_date,
							bitly_link, maps.active_state
					  FROM networks
					  LEFT JOIN posts ON networks.network_id = posts.network_id
					  LEFT JOIN maps ON networks.network_id = maps.network_id
					  WHERE user_id = ?
					  LIMIT 0 , 30";
			
			$pdoStatement = sno_db_interface::executePreparedQueryN($query,array($user));
			$result = $pdoStatement->fetchAll();
			
			$networks = array();
			foreach($result as $net){
				$networks[$net["post_id"]] = array("network_id" => $net["network_id"],"network_name" => $net["network_name"],"network_label" => $net["network_label"],"active_status" => $net["active_status"]);
			}
			echo "<pre>";
			print_r($networks);
			echo "</pre>";
			
			/*
			$bitly_url = $_GET['bitly_array'];
			
			if(!$bitly_url){
				$bitly_url=array('http://bitly.com/fTqjGP','http://bitly.com/g9JN58','http://bitly.com/f56poA','http://bitly.com/fxjOw8','http://bitly.com/fzrlg1');
			}*/
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
				//$piChart->set3D(true);
				//$piChart->setLabels($chart_labels);
				$piChart->addBackgroundFill('bg', '00000000');
				//$piChart-> setProperty('chls', 'FFFFFF,18', false);
				//$piChart->setColors = array("ff3344", "11ff11", "22aacc", "3333aa");
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
				<tr colspan=2 style="font-size:12;"><td><p><br>Bitly URL: <a href="<?php echo $url;?>"><font color="#FFFFFF"><?php echo $url;?></font></a><br>Any more info?</p></td></tr>
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
			
			$("#toggle .feed_details p").click(function () {
			      toggleFeed($(this));
			});
			function toggleFeed(obj){
				$.get("ajax.php",{ 'option' : 1, 'values': $(obj).attr("value") }, function(data){
					$(obj).toggleClass("toggle_inactive", data);
				});
			}
			$("#toggle .feed_details .toggle_active").toggleClass("toggle_inactive", $("#toggle .feed_details .toggle_active").attr("state"));

			

			
			
			$(document).ready(function(){initMenu();});
 		</script>
 	
		<div id="feed_list_id" class="feed_list">
		<div id="toggle">
		<?php 
		// $result
			var_dump($result);
			
			foreach($result as $feed){
			?><p class="feed" align="left"><a href="<?php echo $feed["post_url"];?>"><?php echo $feed["post_id"];?></a><?php echo $feed["publish_date"]; ?></p>
				<div class="feed_details">
					<?php makeGraph($feed["bitly_link"]);?>
					<p class="toggle_active"  value="<?php echo base64_encode(json_encode(array($feed["url"], $feed["active_state"])));?>" state="<?php echo $feed["active_state"];?>">toggle</p>
					<?php echo $feed["post_id"];
						$network = $networks[$feed["post_id"]];
							echo $network["network_name"];
					
					?>
				</div>
			<?php }		?>
		</div>
			
			
		
		</div>
	<div id="resultFeed"></div>