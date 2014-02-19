<?php 
$isMyLender=false;
if(($session->userlevel==PARTNER_LEVEL)||($session->userlevel==ADMIN_LEVEL)) {
	$isMyLender=true;
}
if($session->userlevel==LENDER_LEVEL){
	$isMyLender=$database->isMyLender($id,$session->userid);
}
$b_name=$database->getNameById($ud);	
$loanDetail=$database->getLastloan($ud);
?>
<div id="maincontainer" style="padding-top:10px;">
	<div style="float:left">
		<h3 class="subhead" style="border-bottom:none;margin-bottom:0px"><?php echo $lang['profile']['endorsement']." ".$b_name ?></h3>
	</div>
	<div style="float:right">
		<?php $loanprurl = getLoanprofileUrl($ud,$loanDetail['loanid']);?>
		<a href="<?php echo $loanprurl?>"><?php echo $lang['profile']['back_loan_page']; ?></a>
	</div>
	<div style="clear:both;border-top:1px solid #DFDCDC;height:20px;">&nbsp;</div>
	<div id='feedback_desc'>
<?php	
		$feeddetail=$database->getEndorserRecived($ud);

		if(!empty($feeddetail))
		{	
			$i=0;
			foreach ($feeddetail as $commns)
			{

	$brw=$database->getBorrowerDetails($commns['endorserid']);
	$fb_data = unserialize(base64_decode($brw['fb_data']));
	$endorsedbrwr= $database->getBrwrDetailFrmEndorser($commns['endorserid']);

			?>
				<script type="text/javascript">
					$(document).ready(function() {
						$('#partner_comment<?php echo $i ?>').click(function() {
							$('#partner_comment_desc<?php echo $i ?>').slideToggle("slow");
								var txt = $(this).text();
								if(txt == "<?php echo $lang['profile']['disp_text']; ?>")
									$(this).text("<?php echo $lang['profile']['hide_text']; ?>");
								else
									$(this).text("<?php echo $lang['profile']['disp_text']; ?>");
							});
						$('a#showReply<?php echo $i ?>').click(function() {
							$('#replybox<?php echo $i; ?>').slideToggle("slow");
						});
					});
				</script>
				<a name='f<?php echo $i;?>'></a>
				<table class="zebra-striped">
					<tbody>
					<?php if($commns>0 && $endorsedbrwr['e_candisplay'] ==1) { ?>
						<tr>
														<td>
								<div style="border-bottom:1px solid #cccccc;padding-bottom:5px">
									<div style="margin-left: 14px; float: left;">
										
									<div style="float: right; margin-right: 15px;">
								<?php	if($isMyLender || $displyall)
										{
											echo "<a id='showReply".$i."' href='javascript:void(0)' style='color: #000000;text-decoration:underline'>Reply</a>";
										}
								?>
									</div>
									<div style="clear:both"></div>
								</div>
								<div style="padding-top:5px">
									<table class="detail" style="width:auto">
										<tbody>
											<tr>
												<td style='padding-left:12px'><strong><?php echo $lang['profile']['endorser'];?>:</strong>&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo $commns['ename']; ?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href="<?php echo 'http://www.facebook.com/'.$fb_data['user_profile']['id']; ?>" target="_blank"><?php echo $lang['profile']['endorser_fb']?></a></td>
																												</tr>
											<tr><td>&nbsp;</td></tr>
											<tr><td>&nbsp;</td></tr>
											<tr>
	<td style='padding-left:12px'><strong><?php echo $lang['profile']['endorser_know']." ".$b_name ?>?</strong></td>
											</tr>																	<tr><td>&nbsp;</td></tr>
																						<tr>
	<td style='padding-left:12px'><?php echo $endorsedbrwr['e_know_brwr'];?></td>
											</tr>
											<tr><td>&nbsp;</td></tr>														<tr><td>&nbsp;</td></tr>
											<tr>
	<td style='padding-left:12px'><strong><?php echo $lang['profile']['endorser_conf']." ".$b_name." ".$lang['profile']['endorser_conf2'] ?></strong></td>
											</tr>
											<tr><td>&nbsp;</td></tr>
																						<tr>
	<td style='padding-left:12px'><?php echo $endorsedbrwr['e_cnfdnt_brwr'];?></td>
											</tr>
											<tr>
										</tbody>
									</table>
								</div>
								<div>
									<div style="margin-left: 15px;">
							<?php		if($commns['tr_comment']==null || $commns['tr_comment']=="")
											$comnt = $commns['comment'];
										else
											$comnt = $commns['tr_comment'];
										if(strlen($comnt)>4000)
										{
											
											$prurl = getUserProfileUrl($ud);
											echo nl2br(substr($comnt, 0,4000))."...&nbsp;<a href='$prurl?fdb=1'>more</a>";
										}
										else
											echo nl2br($comnt);
								?>
									</div>

<!-- translation functionality not yet completed
							<?php	

		if(!empty($session->userid))
		{
			if(!empty($commns['tr_comment'])){
				$translation='Edit translation';
			}else{
				$translation='Add translation';
			}
										echo "<div align='right'><a href='index.php?p=24&lc_id=".$commns['id']."&ref=1'>".$translation."</a></div>";
									}
									if($comnt == $commns['tr_comment'])
									{
										echo "<p align='right'><a id='partner_comment".$i."' href='javascript:void(0)'>".$lang['profile']['disp_text']."</a></p>";
									} ?>

-->
								</div>
								<div id='partner_comment_desc<?php echo $i ?>' style="display:none">
									<div style="margin-left: 15px;">
							<?php		if(strlen($commns['comment'])>2000)
										{
											$prurl = getUserProfileUrl($ud);
											echo nl2br(substr($commns['comment'], 0,2000))."...&nbsp;<a href='$prurl?fdb=1'>more</a>";
										}
										else
											echo nl2br($commns['comment']);
								?>
									</div>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<div id="<?php echo $divid;?>" style="border: 1px groove rgb(204, 204, 204); width: 100%; height: auto;margin-bottom:10px;display:none">
					<div style="padding:10px">
						<form  method="POST" action='./updatefeedback.php'>
							<p><strong><?php echo $lang['profile']['comment'] ?></strong></p>
							<div style="float:left">
								<textarea name="txtcomment" style="max-width:560px;width:560px;height:75px"></textarea>
							</div>
							<div style="float:right">
								<input class="btn" name="Submit" type="submit" value="Submit">
								<input type='hidden' name='feedback' />
								<input type='hidden' name='userid' value='<?php echo $ud;?>' />
								<input type='hidden' name='loanid' value='<?php echo $ld;?>'>
								<input type='hidden' name='type' value='<?php echo $commns['type'];?>' />
								<input type='hidden' name='return' value='<?php echo $page;?>' />
								<input type='hidden' name='MessType' value='Feedback' />
								<input type='hidden' name='senderid' value='<?php echo $session->userid;?>' />
								<input type='hidden' name='backComment' value='f<?php echo ($i) ?>' />
							</div>
							<div style="clear:both"></div>
						</form>
					</div>
				</div>
<?php			if(!empty($AllReply))
				{
					$repCount=0;
					$repColor1="#F3FAFA";
					$repColor2="#EBEBEB";
					foreach ($AllReply as $eachreply)
					{
						$sendid1=$eachreply['senderid'];
						$name1=$database->getNameById($sendid1);
						$trColor=$repColor1;
						if($repCount%2==1)
							$trColor=$repColor2;
						$sendid1name = $database->getUserNameById($sendid1);
						$prurl = getUserProfileUrl($sendid1);
			?>
						<div style="margin-left:50px">
							<table class="zebra-striped">
								<tbody>
									<tr>
										<td style="width:100px;background-color:<?php echo $trColor;?>">
											<a href="<?php echo $prurl?>"><img src="library/getimagenew.php?id=<?php echo $sendid1;?>&width=100&height=100" border=0></a>
											<p style="text-align:center"><?php echo "<a href='$prurl'>$name1</a>";?></p>
										</td>
										<td style="background-color:<?php echo $trColor;?>">
											<div><strong><?php echo $name1;?></strong>&nbsp;replied on &nbsp;<?php echo date("M d, Y", $eachreply['editdate']);?></div>
											<div style="margin-top: 5px;">
												<?php if(strlen($eachreply['comment'])>2000){
												$prurl = getUserProfileUrl($ud);
												echo nl2br(substr($eachreply['comment'], 0,2000))."...&nbsp;<a href='$prurl?fdb=1'>more</a>";} else echo nl2br($eachreply['comment']);?>
											</div>
										</td>
									</tr>
								<tbody>
							</table>
						</div>
			<?php	$repCount++;
					}
				}
				++$i;
			}
		}
		else
		{
			echo"<p>No Endorsements Published</p>";
		}?>
	</div>
</div>