<?php
include('database.php');

$class_id=$_GET['cls'];
$section_id=$_GET['sec'];
$exam_id=$_GET['exm'];
 
$sect_id=$section_id;
 

$str=mysql_query("select `roman` from `master_class` where `id`='$class_id'");
$ftr=mysql_fetch_array($str);

$cl_name=$ftr['roman'];

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=Class-".$cl_name."-Report.xls");
header("Content-Type: application/force-download");
header("Cache-Control: post-check=0, pre-check=0", true);
?>
 
                  	<!-- BEGIN BORDERED TABLE PORTLET-->
					<table  style="border-color:#FFF;"id="table"  width="100%" border="1">
					<thead>
								<!--------------------NEW---CONCEPT------------------------>
								<tr>
								<th rowspan='4'>
										 Sr.no
									</th>
									<th rowspan='4'>
										 Name
									</th>
								<?php 
								
								$slt=mysql_query("select * from `master_term` where `id`='$exam_id'");
								$flt=mysql_fetch_array($slt);
								
								$master_term=$flt['name'];
								?>
								<th style="text-align:center" colspan="100">
								<?php echo $master_term; ?>
								</th>
								
								</tr>
								<!------------END-----NEW----COncept------------------------>
								<tr>
									
									<?php 
									 
									$qry=mysql_query("select `subject_id`,`sub_subject_id` from `subject_allocation` where `class_id`='$class_id' && `section_id`='$sect_id' && `elective`='0'");
									while($frq=mysql_fetch_array($qry))
									{
										$sub_id=$frq['subject_id'];
										$sub_subject_id=$frq['sub_subject_id'];
										
										$st=mysql_query("select `subject` from `subject` where `id`='$sub_id'");
										$ft=mysql_fetch_array($st);
										$sub_name=$ft['subject'];
										
										$sst=mysql_query("select `name` from `master_sub_subject` where `id`='$sub_subject_id'");
										$fst=mysql_fetch_array($sst);
										$sub_subject_name=$fst['name'];
										
										$col=0;
										$qt=mysql_query("select `exam_category_type_id` from `exam_mapping` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='$sub_id' && `sub_subject_id`='$sub_subject_id' && `term_id`='$exam_id'");
											while($fqt=mysql_fetch_array($qt))
											{$col++;
												$exam_type_id=$fqt['exam_category_type_id'];
											}
									
									?>
									
									<th style="text-align:center" colspan="<?php echo $col; ?>">
										 <?php echo $sub_name; ?>
										 <?php if(!empty($sub_subject_name)){
											 echo '-'.$sub_subject_name;
										 } ?>

									</th>
									 
									<?php } 
									 
									$qry1=mysql_query("select `elective`,`sub_subject_id` from `subject_allocation` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='0'");
									while($frq1=mysql_fetch_array($qry1))
									{
										$elec_id=$frq1['elective'];
										$elec_sub_subject_id=$frq1['sub_subject_id'];
										
										$st1=mysql_query("select `subject` from `subject` where `id`='$elec_id'");
										$ft1=mysql_fetch_array($st1);
 									    $elec_name=$ft1['subject'];
										  
										$sst1=mysql_query("select `name` from `master_sub_subject` where `id`='$elec_sub_subject_id'");
										$fst1=mysql_fetch_array($sst1);
										$elec_sub_subject_name=$fst1['name'];

										$col=0;
										$qt1=mysql_query("select `exam_category_type_id` from `exam_mapping` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='$elec_id' && `sub_subject_id`='$sub_subject_id' && `term_id`='$exam_id'");
									while($fqt1=mysql_fetch_array($qt1))
									{$col++;
										   $exam_type_id=$fqt1['exam_category_type_id'];
										 
									}
									
									
									?>
									
									<th style="text-align:center" colspan="<?php echo $col; ?>">
										 <?php echo $elec_name; ?>
										  <?php if(!empty($elec_sub_subject_name)){
											 echo '-'.$elec_sub_subject_name;
										 } ?>

									</th>
									 
									<?php } ?>
								</tr>
								
								<tr>
								
								<?php 
								
									$qry=mysql_query("select `subject_id`,`sub_subject_id` from `subject_allocation` where `class_id`='$class_id' && `section_id`='$sect_id' && `elective`='0'");
									while($frq=mysql_fetch_array($qry))
									{
										$sub_id=$frq['subject_id'];
										$sub_subject_id=$frq['sub_subject_id'];
										$x=0;
										$qt=mysql_query("select  DISTINCT(exam_category_id),`exam_category_id` from `exam_mapping` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='$sub_id' && `sub_subject_id`='$sub_subject_id' && `term_id`='$exam_id'");
										while($fqt=mysql_fetch_array($qt))
										{
											//$x++;
											$exam_category_id=$fqt['exam_category_id'];
											$qts=mysql_query("select `exam_category_type_id` from `exam_mapping` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='$sub_id' && `sub_subject_id`='$sub_subject_id' && `term_id`='$exam_id' && `exam_category_id`='$exam_category_id'");
											$count=mysql_num_rows($qts);
											$slt=mysql_query("select * from `exam_category` where `id`='$exam_category_id'");
											$flt=mysql_fetch_array($slt);
											$category_name=$flt['name'];
											?>
											<th colspan="<?php echo $count;?>" style="text-align:center">
											<?php echo $category_name; ?>
											</th>
											<?php
  										}
										
									} 
								
									$qry1=mysql_query("select `elective`,`sub_subject_id` from `subject_allocation` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='0'");
									while($frq1=mysql_fetch_array($qry1))
									{
										$elec_id=$frq1['elective'];
										$sub_subject_id=$frq1['sub_subject_id'];
										
								$qt=mysql_query("select  DISTINCT(exam_category_id),`exam_category_id` from `exam_mapping` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='$elec_id' && `sub_subject_id`='$sub_subject_id' && `term_id`='$exam_id'");
										while($fqt=mysql_fetch_array($qt))
										{
											//$x++;
											$exam_category_id=$fqt['exam_category_id'];
											$qts=mysql_query("select `exam_category_type_id` from `exam_mapping` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='$elec_id' && `sub_subject_id`='$sub_subject_id' && `term_id`='$exam_id' && `exam_category_id`='$exam_category_id'");
											$count=mysql_num_rows($qts);
											$slt=mysql_query("select * from `exam_category` where `id`='$exam_category_id'");
											$flt=mysql_fetch_array($slt);
											$category_name=$flt['name'];
											?>
											<th colspan="<?php echo $count;?>" style="text-align:center">
											<?php echo $category_name; ?>
											</th>
											<?php
  										}}?>
								
								</tr>
								<!---					---->
								<tr>
								
								<?php 
								
									$qry=mysql_query("select `subject_id`,`sub_subject_id` from `subject_allocation` where `class_id`='$class_id' && `section_id`='$sect_id' && `elective`='0'");
									while($frq=mysql_fetch_array($qry))
									{
										$sub_id=$frq['subject_id'];
										$sub_subject_id=$frq['sub_subject_id'];
										
										$qt=mysql_query("select `exam_category_type_id` from `exam_mapping` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='$sub_id' && `sub_subject_id`='$sub_subject_id' && `term_id`='$exam_id'");
										while($fqt=mysql_fetch_array($qt))
										{
											$exam_type_id=$fqt['exam_category_type_id'];
											
											$query=mysql_query("select * from `exam_category_type` where `id`='$exam_type_id'");
											$fetc=mysql_fetch_array($query);
											$Exam=$fetc['Exam'];
											?> 
												<th style="text-align:center">
													<?php echo $Exam; ?>
												</th>
											<?php
										}
									} 
							
								$qry1=mysql_query("select `elective`,`sub_subject_id` from `subject_allocation` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='0'");
								while($frq1=mysql_fetch_array($qry1))
								{
										$elec_id=$frq1['elective'];
										$sub_subject_id=$frq1['sub_subject_id'];
										
									$qt1=mysql_query("select `exam_category_type_id` from `exam_mapping` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='$elec_id' && `sub_subject_id`='$sub_subject_id' && `term_id`='$exam_id'");
									while($fqt1=mysql_fetch_array($qt1))
									{
										  $exam_type_id1=$fqt1['exam_category_type_id'];
										
										$query1=mysql_query("select * from `exam_category_type` where `id`='$exam_type_id1'");
										$fetc1=mysql_fetch_array($query1);

										  $Exam=$fetc1['Exam'];
										
										?>
									 
										<th style="text-align:center">
											  <?php echo $Exam; ?>

										</th>
								<?php }}?>
								
								</tr>
								<!---					---->
								
								
								
								</thead>
								<tbody>
							
									
								<?php 
								$w=0;
								
									$qr=mysql_query("select * from `student` where `class_id`='$class_id' && `section_id`='$section_id' ORDER BY `name`");
									while($fr=mysql_fetch_array($qr))
									{$w++;
									$schlr_id=$fr['id'];
									$scholar_no=$fr['scholar_no'];
									$name=$fr['name'];
									?>
									<tr>
									<td><?php echo $scholar_no; ?></td>
									<td><?php echo $name; ?></td>
								<?php
									$qry=mysql_query("select `subject_id` from `subject_allocation` where `class_id`='$class_id' && `section_id`='$sect_id' && `elective`='0'");
									while($frq=mysql_fetch_array($qry))
									{
										$sub_id=$frq['subject_id'];
										
										$st=mysql_query("select `subject` from `subject` where `id`='$sub_id'");
										$ft=mysql_fetch_array($st);
										
										$sub_name=$ft['subject'];
										$col=0;
										$qt=mysql_query("select `exam_category_id`,`exam_category_type_id`,`max_marks` from `exam_mapping` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='$sub_id' && `term_id`='$exam_id'");
									while($fqt=mysql_fetch_array($qt))
									{$col++;
										$exam_type_id=$fqt['exam_category_type_id'];
										$max_marks=$fqt['max_marks'];
										$exam_category_id=$fqt['exam_category_id'];
									  
										$qst=mysql_query("select `id` from `exam_category_type` where `id`='$exam_type_id'");
										$fst=mysql_fetch_array($qst);

										$retrive_type=$fst['id'];
										$value_sub=0;
									
									
										$sets1=mysql_query("select `id`,`marks` from `student_marks` where `scholar_no`='$scholar_no' && `term_id`='$exam_id' && `subject_id`='$sub_id' && `master_exam_type_id`='$exam_type_id' && `exam_category_id`='$exam_category_id'");
										$fets1=mysql_fetch_array($sets1);
										
										  $value_sub=$fets1['marks'];
										
									 
										 
										
									 
									?>
									
								<td style="text-align:center">
									<?php echo $value_sub; ?> 
								</td>

										<?php } } ?>
										
			<?php	///////////////////////////////////////ELECTIVE	./////////////////////	?>
										
										
										
							<?php
									$qry1=mysql_query("select `elective` from `subject_allocation` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='0'");
									while($frq1=mysql_fetch_array($qry1))
									{
										  $sub_id=$frq1['elective'];
										  
										  
										$qwer=mysql_query("select * from `elective` where `subject_id`='$sub_id' && `scholar_id`='$scholar_no'");
										$count=mysql_num_rows($qwer);
										
										$st1=mysql_query("select `subject` from `subject` where `id`='$sub_id'");
										$ft1=mysql_fetch_array($st1);
										
										$sub_name=$ft1['subject'];
										$col=0;
										$qt=mysql_query("select `exam_category_id`,`exam_category_type_id`,`max_marks` from `exam_mapping` where `class_id`='$class_id' && `section_id`='$sect_id' && `subject_id`='$sub_id' && `term_id`='$exam_id'");
									while($fqt=mysql_fetch_array($qt))
									{$col++;
										$exam_type_id=$fqt['exam_category_type_id'];
										$max_marks=$fqt['max_marks'];
										$exam_category_id=$fqt['exam_category_id'];
										 
										 
									$qst=mysql_query("select `id` from `exam_category_type` where `id`='$exam_type_id'");
									$fst=mysql_fetch_array($qst);
									
									$retrive_type=$fst['id'];
									$value_sub=0;
										
									$sets1=mysql_query("select `id`,`marks` from `student_marks` where `scholar_no`='$scholar_no' && `term_id`='$exam_id' && `subject_id`='$sub_id' && `master_exam_type_id`='$exam_type_id' && `exam_category_id`='$exam_category_id'");
										$fets1=mysql_fetch_array($sets1);
										
										  $value_sub=$fets1['marks'];
										
									 
										 
										
									 
									?>
									
								<td style="text-align:center">
									<?php echo $value_sub; ?> 
								</td>
										<?php } }?></tr><?php }?>
										
									 
								</tbody>
								</table>
				