<?php		
	global $database;
	$Community_organizers = $database->getco_Organizers_Country('AA');
	$Co_section='';
	$count=0;
	if(count($Community_organizers) > 0) {
		foreach ($Community_organizers as $key=>$Organizers) {	
			$Co_section.="<div class='detail mentors'>";
			$Co_section.="<h4>".$Organizers['name']."</h4>";
			if(count($Organizers['co']) > 0) {
				$Co_section.='<ul class="plain">';
				foreach($Organizers['co'] as $key1=> $co_org) {
					$userdetail = $database->getUserById($co_org['user_id']);
					if (!empty($userdetail)) {
						$Community_organizers[$key]['co'][$key1]['name']=$userdetail['name'];
						$Community_organizers[$key]['co'][$key1]['lname']=$userdetail['lastname'];
					}
					if (!empty($co_org['name'])) {						
						$co_lname = $co_org['lname'];
						if(empty($co_lname)){
							$co_lname = end(explode(" ", $co_org['name']));
						}
						$Co_section.="<li>";
							$Co_section.="<span style='display:none'>$co_lname</span>";
							$userdetail = $database->getUserById($co_org['user_id']);
							if($userdetail['userlevel']==BORROWER_LEVEL){
								$loanid= $database->getCurrentLoanid($co_org['user_id']); 
								if(!empty($loanid)){
									$url=getLoanprofileUrl($co_org['user_id'], $loanid);
								}else{
									$url = getUserProfileUrl($co_org['user_id']);
								}
							}else{
								$url = getUserProfileUrl($co_org['user_id']);
							}
							$Co_section.="<a href=$url target='_blank'> ".$co_org['name']."</a>";									
						$Co_section.="</li>";						
					}
				}
				$Co_section.="<li><a href='#' target='_blank'>test</a></li>";
				$Co_section.="<li><a href='#' target='_blank'>test</a></li>";
				$Co_section.="<li><a href='#' target='_blank'>test</a></li>";
				$Co_section.="</ul>";
			}
			$Co_section.="</div>";
		}
		echo $Co_section;
	}
?>	