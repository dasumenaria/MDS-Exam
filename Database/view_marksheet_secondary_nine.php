<?php
include_once('database.php');
include_once('phpqrcode/qrlib.php');
require_once("marksheet_function.php");

	$scholar_no=$_GET['sch'];
	$class_id=$_GET['cls'];
	$section_id=$_GET['sec'];
	$exam_name=$_GET['exm'];
	$term_id=$exam_name;
	
$set=mysql_query("select `name` from `master_term` where `id`='$exam_name'");
	$fet=mysql_fetch_array($set);
	$term=$fet['name'];

	$prmt_id=$class_id+1;
$sset=mysql_query("select `roman` from `master_class` where `id`='$prmt_id'");
	$sfet=mysql_fetch_array($sset);
	$promt_class=$sfet['roman'];
	
	 
$CuttentStatust=mysql_query("select `roman` from `master_class` where `id`='$class_id'");
	$FtcCuttentStatust=mysql_fetch_array($CuttentStatust);
	$CurrentClass=$FtcCuttentStatust['roman'];
  
  
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
<title>Marksheet</title>
	<style>
    .a1
    {width: 1000px; height: auto; border: 1px solid; font-family: Arial, Helvetica, sans-serif; page-break-after:always;
    }
    .center_align {	text-align:center; }
    table
    {
    border-collapse:collapse;
    }
    div
    {
    border-collapse:collapse;
    }
	td {
		text-align:center;	
	}
	 
	.header_font
	{
		font-weight:bold;
		font-size:15px;
	}
	.header_sub
	{
		font-weight:bold;
		font-size:13px;
	}
    </style>
	<style type="text/css">
#watermark {
  color: #d0d0d0;
  font-size: 200pt;
  -webkit-transform: rotate(0deg);
  -moz-transform: rotate(0deg);
  position: absolute;
  width: 70%;
  height: 70%;
  margin: 0;
  z-index: -99;
  left:420px;
  top:750px;
  opacity: 0.2;
}
</style>
</head>
<!-- BEGIN BODY -->
<body>
	<?php 
    //** Find Elevtive Subject In array
	$stdunt=mysql_query("select `id`,`roll_no`,`name` from `student` where `class_id`='$class_id' && `section_id` = '$section_id' && `scholar_no`='$scholar_no'");
    $ftc_stdunt=mysql_fetch_array($stdunt);
	$id=$ftc_stdunt['id'];
	$StudentRollNo=$ftc_stdunt['roll_no'];
	$StudentName=$ftc_stdunt['name'];
 		$stdunt_elev=mysql_query("select `subject_id` from `elective` where `scholar_id`='$scholar_no'");
		while($fte_elev=mysql_fetch_array($stdunt_elev))
		{
			$sub_id_1[]=$fte_elev['subject_id'];
		}
	//**/ END of Elecative Subject 
	//* Header Started
		 ?>
<div class="a1" >
<div id="watermark">
<img src="img/mds.gif" height="250px" width="250px">
</div>
   		<?php header_info_Primary($id,$exam_name);?><br>
  
	<!-- Header End ---> 
    <table width="100%"  cellspacing="0px" cellpadding="0px" border="1" id="sample_1">
		<tbody>
			<tr class="header_font" bgcolor="CCFFCC">
				<td  height="30px" colspan="100">Scholastic Area</td>
			<tr>
			<tr class="header_font" bgcolor="#E0A366">
				 <th height="33" rowspan="2" colspan="2" style="margin-left:5px">Subject / Exam</th>
				 <th height="30px" colspan="100"><?php echo $term; ?></th>
			</tr>
			<tr class="header_font" bgcolor="#E0A366">
				<?php 
                $st=mysql_query("select DISTINCT(term_id) from `master_architecture` where `marksheet_term_id`='$term_id' && `class_id`='$class_id' && `section_id`='$section_id'");
                while($ft=mysql_fetch_array($st))
                {
                    $heading_term=$ft['term_id'];
                    $st3=mysql_query("select `name` from `master_term` where `id`='$heading_term'");
                    $ft3=mysql_fetch_array($st3);
                    $heading_name=$ft3['name'];
					$colspan=0;
					$category_wisecolumn=mysql_query("select * from `master_architecture` where `marksheet_term_id`='$term_id' && `class_id`='$class_id' && `section_id`='$section_id'");
					while($ftc_categorywise=mysql_fetch_array($category_wisecolumn))
					{
						
						$categoryidd=$ftc_categorywise['category_id'];
 						$st4=mysql_query("select DISTINCT(exam_category_id) from `exam_mapping` where `class_id`='$class_id' && `section_id`='$section_id' && `term_id`='$heading_term' && `exam_category_id` = '$categoryidd' ORDER BY `exam_category_id`");
						$countexam_mapping=mysql_num_rows($st4);
						if($countexam_mapping>0)
						{	
							while($ft4=mysql_fetch_array($st4))
							{	
								$find_id=$ft4['exam_category_id'];
								$st7=mysql_query("select `name` from `exam_category` where `id`='$find_id'");
								$ft7=mysql_fetch_array($st7);
								$category_name=$ft7['name'];
								$colspan++;
							} 	
						}
						else
						{ for($x=0; $x<$countArchitecure; $x++){echo"<td></td>";}}
						
					
					 if($find_id==2){continue;}
					?>
					<th height="30px" width="25%"><b><?php echo $category_name; ?></b></th>
					<?php
                } }
                ?>	
            <th><b>Total</b></th>
 			
         </tr>
		  
		<!--- A Max+
				L T M Max marks=0 ------------------------> 
         	<?php 
 			$OverAllTotalGetMarks=0;
			$OverAllTotalMaxMarks=0;
			$Result=0;
			$FailedInSubSubject=array();
			$FaildInSubject=array();
			///*- SUVJECT ALLOCSTYION LOOP
		$SNo=0;
		$SNotot=0;
		$SubjectDataQuery=mysql_query("select * from `subject` order by `order_type` ASC ");
		while($FtcSubjectDataQuery=mysql_fetch_array($SubjectDataQuery))
		{
			$SubjectIdGrade=$FtcSubjectDataQuery['id'];
			 
		$FindSubject=mysql_query("select distinct `subject_id`,`elective` from `subject_allocation` where `class_id`='$class_id'  && `section_id`='$section_id' && ( `subject_id` ='$SubjectIdGrade' || `elective` ='$SubjectIdGrade' )");
			while($ftc_subject=mysql_fetch_array($FindSubject))
			{
 				$subject_id=$ftc_subject['subject_id'];
				if(empty($subject_id))
				{
					$subject_id=$ftc_subject['elective'];
 					
					$ElectiveQuery=mysql_query("select * from `elective` where `scholar_id`='$scholar_no' && `subject_id`='$subject_id'");
					$ElectiveQueryCount=mysql_num_rows($ElectiveQuery);
					if($ElectiveQueryCount==0)
					{
						continue;
					}
				}
				$sub_subject_id=$ftc_subject['sub_subject_id'];
				
				$qry=mysql_query("select `subject`,`elective`,`grade` from `subject` where `id`='$subject_id'");
				$fet=mysql_fetch_array($qry);
				$subject=$fet['subject'];
				$grade=$fet['grade'];
				
				$qtr=mysql_query("select `name` from `master_sub_subject` where `id`='$sub_subject_id'");
				$ftr=mysql_fetch_array($qtr);
				$sub_subject_name=$ftr['name'];
				
				if($grade=='G')
				{
					continue;
				}
				$col_span_sub=0;
				$sub_count=0;
				$slt=mysql_query("select DISTINCT(`sub_subject_id`) from `exam_mapping` where `class_id`='$class_id' && `section_id`='$section_id' && `subject_id`='$subject_id'");
				$sub_sub_count=mysql_num_rows($slt);
				if($sub_sub_count>0){
					$sub_count=$sub_sub_count;
				}
				if($sub_count==1)
				{$col_span_sub=2;}
				 
				?>
                 <tr  class="<?php if($sub_sub_count>1){ echo "subsubject";}?>">
                    <th height="33" width="25%" class="header_sub " colspan="<?php echo $col_span_sub;?>" style="margin-left:5px" rowspan="<?php echo $sub_count; ?>">
					<?php echo $subject; ?></th> 
					<?php 
			if($sub_count>0)
			{
			 while($flt=mysql_fetch_array($slt))
			 { 
						$sub_subject_id=$flt['sub_subject_id'];
						
						$slt1=mysql_query("select `name` from `master_sub_subject` where `id`='$sub_subject_id'");
						$flt1=mysql_fetch_array($slt1);
						$sub_sub_name=$flt1['name'];
						if($sub_subject_id)
						{?>
							<th class="header_sub"  height="25px" width="10%"><?php echo $sub_sub_name; ?></th>
					<?php } ?>
					
					
                <?php
					$TotalMaxMarks=0;
					$TotalGetMarks=0;
					$forCOl=0;
					$x=array();
					$y=array();
 				//* Architacher Loop
					$ArchitacherQuery=mysql_query("select DISTINCT(term_id) from `master_architecture` where `marksheet_term_id`='$term_id' && `class_id`='$class_id' && `section_id`='$section_id'");
					while($ftc_ArchitacherQuery=mysql_fetch_array($ArchitacherQuery))
					{  
						$ftc_ArchitacherQueryTerm_id=$ftc_ArchitacherQuery['term_id'];
						
						$total_one=0;
						$category_wisecolumn=mysql_query("select * from `master_architecture` where `marksheet_term_id`='$term_id' && `class_id`='$class_id' && `section_id`='$section_id'");
						while($ftc_categorywise=mysql_fetch_array($category_wisecolumn))
						{  
							$categoryidd=$ftc_categorywise['category_id'];
							$exam_category_query=mysql_query("select DISTINCT(exam_category_id) from `exam_mapping` where `class_id`='$class_id' && `section_id`='$section_id' && `term_id`='$ftc_ArchitacherQueryTerm_id' && `subject_id`='$subject_id' && `sub_subject_id`='$sub_subject_id' && `exam_category_id` = '$categoryidd' ORDER BY `exam_category_id` ASC");
							$Countexam_category_query=mysql_num_rows($exam_category_query);
							while($exam_category_Fetch=mysql_fetch_array($exam_category_query))
							{ $SNo++;
							  $SNotot++;
								$FetchExamCategoryId=$exam_category_Fetch['exam_category_id'];
								$TotalOneSubject=0;
								$TotalOneSubjectMax=0;
								$dummy_add=0;
								
								//** Exam Mapping Table ------- FInd Exam Category TYpe
								$exam_categoryTYpe_query=mysql_query("select `exam_category_type_id`,`max_marks`,`reduse`,`reduse_to` from `exam_mapping` where `class_id`='$class_id' && `section_id`='$section_id' && `term_id`='$ftc_ArchitacherQueryTerm_id' && `subject_id`='$subject_id' && `sub_subject_id`='$sub_subject_id' && `exam_category_id`='$FetchExamCategoryId' ORDER BY `exam_category_id` ASC");
								$l=0;
								$reduse_mark=0;
								$dummy_max_marks=0;
								$PTMarks=array();
								$PTOneMarks=array();
								while($exam_categoryType_Fetch=mysql_fetch_array($exam_categoryTYpe_query))
								{
									
									$exam_category_type_id=$exam_categoryType_Fetch['exam_category_type_id'];
									$MainMaxMarks=$exam_categoryType_Fetch['max_marks'];
									$reduse=$exam_categoryType_Fetch['reduse'];
									$reduse_to=$exam_categoryType_Fetch['reduse_to'];
									// Count Total Max Marks One subject and Overall
									
									$StudentMarksQuery=mysql_query("select * from `student_marks` where `scholar_no`='$scholar_no' && `term_id`='$ftc_ArchitacherQueryTerm_id' && `exam_category_id`='$FetchExamCategoryId' && `subject_id`='$subject_id' && `sub_subject_id`='$sub_subject_id' && `master_exam_type_id` = '$exam_category_type_id'");
									$FetchStudentMarks=mysql_fetch_array($StudentMarksQuery);
									$SubjectMarks=$FetchStudentMarks['marks'];
									// Count Total Get Marks One subject and Overall
									//--- ATML COnsept
									if($SubjectMarks=='A'){}
									if($SubjectMarks=='M'){$MainMaxMarks=0; $SubjectMarks='M';}
									if($SubjectMarks=='T'){$MainMaxMarks=0; $SubjectMarks='T';}
									if($SubjectMarks=='L'){$MainMaxMarks=0;$SubjectMarks='L';}
									
									if($reduse=='no'){
										$TotalMaxMarks+=$MainMaxMarks;
										$OverAllTotalMaxMarks+=$MainMaxMarks;
									}
									if($categoryidd==1 || $categoryidd==2){ }else{
										$TotalGetMarks+=$SubjectMarks;
									}
									$TotalOneSubject+=$SubjectMarks;
									$TotalOneSubjectMax+=$MainMaxMarks;
									$OverAllTotalGetMarks+=$SubjectMarks;
									 
									
									if($categoryidd==1){
										if(($SubjectMarks=='A') || ($SubjectMarks=='T') || ($SubjectMarks=='M') || ($SubjectMarks=='L')){
										
										//$SubjectMarks=0;
										}
									$PTMarks[]=$SubjectMarks;
									$x=$PTMarks;
									}
									if($categoryidd==2){
										if(($SubjectMarks=='A') || ($SubjectMarks=='T') || ($SubjectMarks=='M') || ($SubjectMarks=='L')){
										
										//$SubjectMarks=0;
										}
									$PTOneMarks[]=$SubjectMarks;
									$y=$PTOneMarks;
									$TM+=$MainMaxMarks;
									$TotalMaxMarks=$TotalMaxMarks-$MainMaxMarks;
 									}
									
								}
 								if($categoryidd==1 || $categoryidd==2){ continue;}
								else
								{
									
									?>
									<td>
									<?php
									 
										$totallz='';
										for ($xi = 0; $xi < sizeof($x); $xi++) {
											$result_array=array_diff($x,$y);
											$result_array1=array_diff($y,$x);
											if(sizeof($result_array)>0 || sizeof($result_array1)>0)
											{ 
												if ($x[$xi] < $y[$xi] && $y[$xi] !='A' && $y[$xi] !='T' && $y[$xi] !='M' && $y[$xi] !='L'){
													$grater=$y[$xi];
													$totallz+=$grater;
												}
												if ($x[$xi] >= $y[$xi] && $x[$xi] !='A' && $x[$xi] !='T' && $x[$xi] !='M' && $x[$xi] !='L'){
													$grter=$x[$xi];
													$totallz+=$grter;
												}
												else{
													$grter=$y[$xi];
													$totallz+=$grter;
												}
											}
											else
											{  
												$totallz=$x[$xi];								
											}
										}
										
										if($totallz=='' && $TM==0)
										{
											echo "-";  
										}
										else if($totallz =='' && $TM>0)
										{
											echo '0/'.$TM;
										}
										else
										{
											echo $totallz.'/'.$TM;
										}
										$TotalGetMarks+=$totallz;
										$TM=0; 
										/*echo "<pre>";
										print_r($x);
										print_r($y);
										echo "</pre>";
										exit;*/
										?>
									</td>
									<td>
										<?php echo $TotalOneSubject.'/'.$TotalOneSubjectMax; ?>
									</td>
									<?php
								}
							$forCOl++;;
							}
 						}
					}
					//** END Exam Mapping Table ------- FInd Exam Category 
 						$MinumumPassingPercentage=(($TotalMaxMarks/100)*33);
						if($TotalGetMarks<$MinumumPassingPercentage)
						{
							$Result+=1;
							$FailedInSubSubject[]=$sub_subject_id;
							$FaildInSubject[]=$subject;
						}
						$tot_avg=(($TotalGetMarks/$TotalMaxMarks)*100)
						?>
                         	<th>
								<?php 
								echo $TotalGetMarks.'/'.$TotalMaxMarks;
								?>
							 </th>
                         <?php
						//** FInd Grade
						if($TotalGetMarks==0 || $TotalMaxMarks==0){$GetOneSubjectPercentage=0;}
						else
						{
							$GetOneSubjectPercentage=(($TotalGetMarks/$TotalMaxMarks)*100);
						}
						
						if($GetOneSubjectPercentage>=75)
						{  
							$DistInSubject[]=$subject;
						}
							$GradeQuery=mysql_query("select `grade` from `master_grade` where `class_id`='$class_id' && `section_id`='$section_id' && `range_from`<='$GetOneSubjectPercentage' && `range_to`>='$GetOneSubjectPercentage'");
							$FtcGradeQuery=mysql_fetch_array($GradeQuery);
							$grade=$FtcGradeQuery['grade'];
						//exit;?>
 						</tr>
						<?php
						$SNo=0;
						$SNotot=0;
					 
				//* END  Architacher Loop
					} 
				}
			}
		}
 			///*- END SUVJECT ALLOCSTYION LOOP
 			?>
             
  		</tbody>
	</table>
		
		 <?php
						 //** Calculate Percentage
							$GetPercentage=(($OverAllTotalGetMarks*100)/$OverAllTotalMaxMarks);
							$OverAllPersentage=number_format($GetPercentage,2);
						//*** Check Fail Or Promote
						//FailedInSubSubject    FaildInSubject
						if($Result=='0')
						{
							$status="Promoted to Class ".$promt_class;
							$Promotion=$promt_class;
							$FinalStatusOfResult="Pass";
						}
						else if($Result=='1')
						{   
							$c=0;
							$FinalStatusOfResult="Supplementary";
							$StatusOfSubSubject='';
							$DistSubject='';
							foreach($FaildInSubject as $sub)
							{
								$FailedInSubSubject[$c];
								$DistInSubject[$c];
								$DistInSubSubject[$c];
								if($FailedInSubSubject[$c]!='')
								{
									$StatusOfSubSubject=$sub.'('.$FailedInSubSubject[$c].')';
								}
								else
								{
									$StatusOfSubSubject=$sub;
								}
								//-- DIST
							}
							$status=$StatusOfSubSubject;
							$Compartment=$StatusOfSubSubject;
						} 
						else if($Result>1)
						{
							$status="Detained in Class ".$CurrentClass;
							$Detained=$CurrentClass;					
							$FinalStatusOfResult="Fail";
							$c=0;
							foreach($FaildInSubject as $sub)
							{
								$FailedInSubSubject[$c];
								if($FailedInSubSubject[$c]!='')
								{
									$sub.'('.$FailedInSubSubject[$c].')';
								}
								else
								{
									$sub;
								}
 							}
						} 
						else {}
					//-//*** End Check Fail Or Promote
					
					//*** Student Result Table ENtry
						mysql_query("delete from `student_result` where `scholar_no`='$scholar_no' && `roll_no`='$StudentRollNo' && `class_id`='$class_id' && `section_id`='$section_id' && `term_id`='$term_id'");
						
						if($FinalStatusOfResult=='Pass')
						{
							$next_class=$prmt_id;
						}
						else
						{
							$next_class=$class_id;
						}
						mysql_query("insert into `student_result` SET `class_id`='$class_id',`section_id`='$section_id',`roll_no`='$StudentRollNo',`scholar_no`='$scholar_no',`status`='$status',`final_status`='$FinalStatusOfResult',`per`='$OverAllPersentage',`total`='$OverAllTotalGetMarks',`term_id`='$term_id',`total_marks`='$OverAllTotalMaxMarks',`next_class_id`='$next_class'");
						
						?>
	




<br> 
<!----------------------Co-Scholastic-START--------------------->
<table width="100%" cellspacing="0px" cellpadding="0px" border="1" id="sample_1">
		<tbody>
			<tr class="header_font" bgcolor="CCFFCC">
				 <th height="35" colspan="102" style="margin-left:5px">
					PERFORMANCE - APPRAISAL AND FEEDBACK
				 </th>
			</tr>
			<!--tr class="header_font" bgcolor="#E0A366">
				<th height="30px" colspan="3"><?php //echo $term; ?></th>
			</tr-->
			<tr class="header_font" bgcolor="#E0A366">
				<th height="33" width="40%" style="margin-left:5px">Field</th>
				<th>Feedback</th>
			</tr>
			
			<?php 
			$sst=mysql_query("select * from `master_health`");
			while($fft=mysql_fetch_array($sst))
			{
				$health_id=$fft['id'];
				$health_name=$fft['health_type'];
				
				
				$sst1=mysql_query("select `value` from `student_health` where `scholar_no`='$scholar_no' && `master_health_id`='$health_id'");
				$fft1=mysql_fetch_array($sst1);
				$health_marks=$fft1['value'];
				
				$sst2=mysql_query("select `description` from `health_category` where `health_id`='$health_id' && `health_point`='$health_marks'");
				$fft2=mysql_fetch_array($sst2);
				$health_description=$fft2['description'];
				?>
			<tr>
				<td height="40px" style="text-align:left;">&nbsp;&nbsp;&nbsp;
					<?php echo $health_name; ?>
				</td>
				<td style="text-align:left;">&nbsp;&nbsp;&nbsp;
					<?php echo $health_description; ?>
				</td>
			</tr>
		<?php	} ?>
			
		</tbody>
	</table>
<!----------------------Co-Scholastic-END----------------------->

<!-------------------Grade Point START---------------------------->
<table width="100%" border="0" >
            <tr>
             
			<?php 
			/* if($result<'1'){
						
						  $status="Promoted to Class ".$promt_class;
						  $final_status="Pass";
					}
					else if($result=='1'){   
					$c=0;
					
						foreach($faild_subject as $sub)
						{
							$fail_sub_sub[$c];
							if($fail_sub_sub[$c]!='')
							{
							  $stt=$sub.'('.$fail_sub_sub[$c].')';
							}
							else
							{
								  $stt=$sub;
							}
							   //$status="Supplementary in ".$stt;
							  $final_status="Supplementary";
						}
					} 
					else if($result>1){
					 // $status="Detained in Class ".$detain_class." - ".$promt_section; 
					 $final_status="Fail";
					$c=0;
						foreach($faild_subject as $sub)
						{
							$fail_sub_sub[$c];
							if($fail_sub_sub[$c]!='')
							{
							  $sub.'('.$fail_sub_sub[$c].')';
							}
							else
							{
								  $sub;
							}
						 
						}
					} else {}
					
	mysql_query("delete from `extra` where `roll_no`='$Roll',`exam_name`='$exam_name'");
	//echo "insert into `extra` SET `class_id`='$class_id',`section_id`='$section_id',`exam_name`='$exam_name',`roll_no`='$Roll',`name`='$name',`status`='$status',`per`='$per'";	
	mysql_query("insert into `extra` SET `class_id`='$class_id',`section_id`='$section_id',`roll_no`='$Roll',`name`='$name',`status`='$status',`final_status`='$status1',`per`='$per',`total`='$all',`exam_name`='$exam_name'");
			 */		
			?>
			<br>
			<td width="39%"> 
           		<table height="350" width="100%" border="1" cellspacing="0" cellpadding="0" style="text-align:center" >
                    <tr bgcolor="CCFFCC" class="header_font">
                    	<th colspan="1" scope="col" height="35" >Performance Improvement Tips </th>
                    </tr>
                    <tr style="font-size:16px" >
                        <td width="135" style="text-align:center; padding-left:15px" height="35">
							<i>
								Break the syllabus into smaller portions
							</i>
						</td>
                    </tr>
					<tr style="font-size:16px" >
                        <td width="135" style="text-align:center; padding-left:15px" height="35">
							<i>
								Make a Daily Time Table with specific achievable targets
							</i>	
						</td>
                    </tr>
					<tr style="font-size:16px" >
                        <td width="135" style="text-align:center; padding-left:15px" height="35">
							<i>
								Take notes in the class 
							</i>	
						</td>
                    </tr>
					<tr style="font-size:16px" >
                        <td width="135" style="text-align:center; padding-left:15px" height="35">
							<i>
								Consult reference books and school notes and books to make specific and comprehensive study notes
							</i>	
						</td>
                    </tr>
					<tr style="font-size:16px" >
                        <td width="135" style="text-align:center; padding-left:15px" height="35">
							<i>
								Eliminate un-necessary distractions
							</i>	
						</td>
                    </tr>
					<tr style="font-size:16px" >
                        <td width="135" style="text-align:center; padding-left:15px" height="35">
							<i>
								Ask questions and respond actively in class
							</i>	
						</td>
                    </tr>
					<tr style="font-size:16px" >
                        <td width="135" style="text-align:center; padding-left:15px" height="35">
							<i>
								Discuss your progress/ doubts with teachers, friends and guides
							</i>	
						</td>
                    </tr>
            </table>
           </td>
            <td width="58%"> 
           		<table height="348" width="100%" border="1" cellspacing="0" cellpadding="0" style="text-align:center" >
                    <tr bgcolor="CCFFCC" class="header_font">
                    	<th colspan="2" scope="col" height="42" >Session Report (Signature)</th>
                    </tr>
                    <tr style="font-size:16px" bgcolor="#E0A366" class="header_font"  >
                        <td width="135" style="text-align:left; padding-left:15px" height="35"><strong>Designation</strong></td>
                        <td width="135"><strong>Signature</strong></td>
                    </tr>
					<?php
					$st=mysql_query("select `attendance` from `attendance` where `scholar_no`='$scholar_no' && `term`='$exam_name'");
				$ft=mysql_fetch_array($st);
				
				$attendance=$ft['attendance'];
				$max_attendance=$ft['max_attendance'];
				$show_attendance=$attendance.'/'.$max_attendance;
					?>
					<tr>
                        <td style="text-align:left; padding-left:15px"  width="38%">Attendance</td>
                        <td>&nbsp;<?php echo $show_attendance; ?></td>
                    </tr>
                     
                    <tr>
                        <td style="text-align:left; padding-left:15px">Class Teacher</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:left; padding-left:15px">Parent</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                    	<td  style="text-align:left; padding-left:15px">Date of Issue</td>	
                        <td ><?php echo date('d-M-Y'); ?></td>	
                    </tr>
                    <tr>
                    	<td  style="text-align:left; padding-left:15px">Remarks</td>
                        <td><input type="text" style="border:0;font-size:15px;text-align:center;" value="<?php //echo $status; ?>"></td>
                    </tr>

		     <tr height="75px">
                        <td style="text-align:center; font-size:18px">Principal<br>( Seal & Signature )</td>
                   		<td>&nbsp;</td>
                    </tr>
           	 	</table>
           </td>
        </tr>
        </table>
<!-------------------Grade Point END---------------------------->

 
</div>
</body>
</html>
