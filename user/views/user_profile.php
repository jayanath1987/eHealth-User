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
with this program. If not, see <http://www.gnu.org/licenses/> 




---------------------------------------------------------------------------------- 
Date : June 2016
Author: Mr. Jayanath Liyanage   jayanathl@icta.lk

Programme Manager: Shriyananda Rathnayake
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
			echo '<div class="panel-heading" ><b>My Profile / settings</b></div>';
				echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
					echo '<tr >';
						echo '<td width=20%>';
							echo 'Full name: ';
						echo '</td>';
						echo '<td>';
							echo $user_info["Title"].' '.$user_info["FirstName"].' '.$user_info["OtherName"];
						echo '</td>';
					echo '</tr>';					
					echo '<tr >';
						echo '<td width=20%>';
							echo 'Date of birth: ';
						echo '</td>';
						echo '<td>';
							echo $user_info["DateOfBirth"];
						echo '</td>';
					echo '</tr>';
						echo '<td width=20%>';
							echo 'Gender: ';
						echo '</td>';
						echo '<td>';
							echo $user_info["Gender"];
						echo '</td>';
					echo '</tr>';
						echo '<td width=20%>';
							echo 'Civil status: ';
						echo '</td>';
						echo '<td>';
							echo $user_info["CivilStatus"];
						echo '</td>';
					echo '</tr>';					
					echo '<tr >';
						echo '<td width=20%>';
							echo 'User Group: ';
						echo '</td>';
						echo '<td>';
							echo $user_info["UserGroup"];
						echo '</td>';
					echo '</tr>';					
					echo '<tr >';
						echo '<td width=20%>';
							echo 'User Name: ';
						echo '</td>';
						echo '<td>';
							echo $user_info["UserName"];
						echo '</td>';
					echo '</tr>';

				echo '</table>';
		echo '</div>';
 
 
 		echo '<div class="panel panel-info"  style="padding:2px;margin-bottom:1px;" >';
			echo '<div class="panel-heading" ><b>My saved favorite drug list</b></div>';
				echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
					if(empty($drug_list)){
                        echo "List Empty";
                    }
                    //print_r($drug_list);
                    for ($i=0; $i<count($drug_list); $i++){
                        echo '<tr >';
                            echo '<td><b>'.($i+1);
                             echo '</b></td>';
                            echo '<td width=90%>';
                                echo '<b>'.$drug_list[$i]["name"].'</b>';
                            echo '</td>';
                            echo '<td>';
                                echo '<a href="'.site_url('form/edit/user_favour_drug/'.$drug_list[$i]["user_favour_drug_id"]).'" class="">Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;';
                                //echo '<a href="" class="">Remove</a>';
                            echo '</td>';
                        echo '</tr>';
                        echo '<tr >';
                            echo '<td colspan=3>';
                                //echo var_dump($drug_list[$i]["drug_items"]);
                                if (empty($drug_list[$i]["drug_items"])){
                                     echo "List Empty";
                                }
                                echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
                                for ($j=0; $j<count($drug_list[$i]["drug_items"]); $j++){
                                     echo '<tr >';
                                        echo '<td align=right>'.($j+1);
                                        echo '</td>';
                                        echo '<td width=85%>';
                                            echo '<i>'.$drug_list[$i]["drug_items"][$j]["name"].'-';
                                             echo $drug_list[$i]["drug_items"][$j]["formulation"].'-';
                                             echo $drug_list[$i]["drug_items"][$j]["dose"].'-';
                                             echo $drug_list[$i]["drug_items"][$j]["frequency"].'-';
                                             //user_favour_drug_items_id
                                             echo $drug_list[$i]["drug_items"][$j]["how_long"].'</i>';
                                        echo '</td>';
                                        echo '<td>';
                                            echo '<a href="'.site_url('form/edit/user_favour_drug_items/'.$drug_list[$i]["drug_items"][$j]["user_favour_drug_items_id"]).'" class="">Edit</a>';
                                        echo '</td>';
                                    echo '</tr>';
                                }
                                echo '</table>';
                            echo '</td >';
                        echo '</tr >';
                    }

				echo '</table>';
		echo '</div><br><br><br>';
?>		
	  </div>
	</div>
	
</div>
