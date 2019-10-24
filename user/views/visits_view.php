<?php
/*
--------------------------------------------------------------------------------
HHIMS - Hospital Health Information Management System
Copyright (c) 2011 Information and Communication Technology Agency of Sri Lanka
<http: www.hhims.org/>
----------------------------------------------------------------------------------
This program is free software: you can redistribute it and/or modify it under the
terms of the GNU Affero General Public License as published by the Free Software 
Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along 
with this program. If not, see <http://www.gnu.org/licenses/> or write to:
Free Software  HHIMS
ICT Agency,
160/24, Kirimandala Mawatha,
Colombo 05, Sri Lanka
---------------------------------------------------------------------------------- 
Author: Author: Mr. Jayanath Liyanage   jayanathl@icta.lk
                 
URL: http://www.govforge.icta.lk/gf/project/hhims/
----------------------------------------------------------------------------------
*/

	include("header.php");	///loads the html HEAD section (JS,CSS)
?>
<?php echo Modules::run('menu'); //runs the available menu option to that usergroup ?>
<div class="container" style="width:95%;">
	<div class="row" style="margin-top:55px;">
	  <div class="col-md-2 ">
		<?php echo Modules::run('leftmenu/user'); //runs the available left menu for preferance ?>
        <a href="<?php echo site_url($this->session->userdata("RET_PAGE") ); ?>" class="btn btn-primary">Continue work</a>
	  </div>
	  <div class="col-md-10 ">
		  <?php
				echo '<div class="panel panel-info"  style="padding:2px;margin-bottom:1px;" >';
					echo '<div class="panel-heading" ><b>My Consultations on <input id = "vdate" type="date" value="'.$vdate.'"><button id="displaybtn" class="btn btn-primary btn-xs">Display</button></b></div>';
					if (empty($visit_list)){
						echo '-- NO DATA --';
					}
					else{
						echo '<table class="table" >';
								for ($i=0 ; $i < count($visit_list); $i++){
									echo '<tr>';
										echo '<td>';
											echo Modules::run('patient/print_hin',$visit_list[$i]['HIN']);
										echo '</td>';
										echo '<td>';
											echo '<a href="'.site_url("patient/view/".$visit_list[$i]['PID']).'" >'.$visit_list[$i]['patient_name'].'</a>';
										echo '</td>';
										echo '<td>';
											echo $visit_list[$i]['Gender'];
										echo '</td>';
										echo '<td>';
											echo '<b>'.$visit_list[$i]['Complaint'].'</b>';
										echo '</td>';
										echo '<td>';
											echo $visit_list[$i]['village'];
										echo '</td>';
										echo '<td>';
											echo '<a  href="'.site_url("opd/view/".$visit_list[$i]['OPDID']).'">Open visit</a>';
										echo '</td>';
									echo '</tr>';
								}
						echo '</table>';
					}
				echo '</div>';
				
			?>
	  </div>
	</div>	
</div>
<script>
  $(function() {
	$("#displaybtn").click(function(){
			self.document.location="<?php echo site_url('user/visits'); ?>/"+$("#vdate").val();
	});
  });
  </script>
