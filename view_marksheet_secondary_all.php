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
</head>
<!-- BEGIN BODY -->
<body>
	<?php 
    //** Find Elevtive Subject In array
	$stdunt=mysql_query("select `id`,`roll_no`,`name`,`scholar_no` from `student` where `class_id`='$class_id' && `section_id` = '$section_id'");
    while($ftc_stdunt=mysql_fetch_array($stdunt))
	{
		$id=$ftc_stdunt['id'];
		$StudentRollNo=$ftc_stdunt['roll_no'];
		$StudentName=$ftc_stdunt['name'];
		$scholar_no=$ftc_stdunt['scholar_no'];
 		$stdunt_elev=mysql_query("select `subject_id` from `elective` where `scholar_id`='$scholar_no'");
		while($fte_elev=mysql_fetch_array($stdunt_elev))
		{
			$sub_id_1[]=$fte_elev['subject_id'];
		}
	//**/ END of Elecative Subject 
	//* Header Started
		 ?>
<div class="a1">
   		<?php header_info_Primary($id,$exam_name);?><br>
  
	<!-- Header End ---> 
    <table width="100%"  cellspacing="0px"  cellpadding="0px" border="1" id="sample_1">
		<tbody>
			<tr class="header_font" bgcolor="CCFFCC">
				<td  height="25px" colspan="100">Part 1 : Scholastic Area</td>
			<tr>
			<tr class="header_font" bgcolor="#E0A366">
				 <th height="28" rowspan="2" colspan="2" style="margin-left:5px">Subject / Exam</th>
				 <th height="28px" colspan="100"><?php echo $term; ?></th>
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
						
					
					//if($colspan==1){$colspan=0;}
					?>
					<th height="30px"><b><?php echo $category_name; ?></b></th>
					<?php
                } }
                ?>	
            <th>Over All Total</th>
            <th>Grade</th>
			
         </tr>
		 
		 <!----------MAX--MARKS--START----------->
		 <!--<tr class="header_font" bgcolor="#E0A366"><th colspan="2">Max Marks</th>
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
						$tot_max=0;
 						$st4=mysql_query("select DISTINCT(exam_category_type_id),max_marks,`reduse_to`,`reduse`,`exam_category_id` from `exam_mapping` where `class_id`='$class_id' && `section_id`='$section_id' && `term_id`='$heading_term' && `exam_category_id` = '$categoryidd' ORDER BY `exam_category_id`");
						$countexam_mapping=mysql_num_rows($st4);
						if($countexam_mapping>0)
						{	
							$f=0;
							while($ft4=mysql_fetch_array($st4))
							{	
								$find_id=$ft4['exam_category_id'];
								$reduse=$ft4['reduse'];
								$reduse_to=$ft4['reduse_to'];
								$view_max_marks=$ft4['max_marks'];
								$tot_max+=$view_max_marks;
 							} 	
						}
						else
						{ for($x=0; $x<$countArchitecure; $x++){echo"<td></td>";}}
						
					
					//if($colspan==1){$colspan=0;}
					?>
					<th height="30px"><b><?php echo $tot_max; ?></b></th>
					<?php
						$all_view_max_marks+=$tot_max;

                } }
                ?>	
            <th><?php echo $all_view_max_marks; ?></th>
            <th></th>
			
         </tr>----->
		 
		 <!----------MAX--MARKS--END----------->
		 
		<!--- A Max+
				L T M Max marks=0 ------------------------> 
		<?php 
		$all_view_max_marks=0;
		$OverAllTotalGetMarks=0;
		$OverAllTotalMaxMarks=0;
		$TotalGetMarks=0;
		$Result=0;
		$FailedInSubSubject=array();
		$FaildInSubject=array();
		///*- SUVJECT ALLOCSTYION LOOP
		$SNo=0;
		$SNotot=0;
		$SubjectDataQuery=mysql_query("select * from `subject` where `id` !='43' order by `order_type` ASC ");
		while($FtcSubjectDataQuery=mysql_fetch_array($SubjectDataQuery))
		{
			$SubjectIdGrade=$FtcSubjectDataQuery['id'];
		
			$FindSubject=mysql_query("select distinct `subject_id`,`elective` from `subject_allocation` where `class_id`='$class_id'  && `section_id`='$section_id' && `subject_id` ='$SubjectIdGrade'");
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
                 <tr class="<?php if($sub_sub_count>1){ echo "subsubject";}?>">
                    <th height="33" width="15%" class="header_sub " colspan="<?php echo $col_span_sub;?>" style="margin-left:5px" rowspan="<?php echo $sub_count; ?>">
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
								//$TotalGetMarks=0;
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
									}else if(($reduse=='yes')){
										
										$l=1;
										$TotalMaxMarks+=$reduse_to;
										$OverAllTotalMaxMarks+=$reduse_to;
									}
									if($reduse=='yes'){
										$reduse_mark=$reduse_to;
									}
									$dummy_max_marks+=$MainMaxMarks;
									
									$reduse_calculation=0; 
									if($reduse=='no'){
										$TotalGetMarks+=$SubjectMarks;
										$TotalOneSubject+=$SubjectMarks;
$TotalOneSubjectMax+=$MainMaxMarks;
										$OverAllTotalGetMarks+=$SubjectMarks;
									}else{
										$dummy_add+=$SubjectMarks;
									}
									
								}
								 
								if($reduse=='yes'){
										$mark_reduse=$dummy_add;
										$reduse_mark;
										$dummy_max_marks;
										$reduce_percentage=(($reduse_mark*100)/$dummy_max_marks);	 
										$reduse_calculation=(($mark_reduse*$reduce_percentage)/100);
										$TotalGetMarks+=$reduse_calculation;
										$TotalOneSubject+=$reduse_calculation;
$TotalOneSubjectMax+=$TotalOneSubject;
										$OverAllTotalGetMarks+=$reduse_calculation;
									}
								 
								?>
								<td>
									<?php echo $TotalOneSubject.'/'.$TotalOneSubjectMax; ?>
								</td>
							<?php
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
								echo round($TotalGetMarks).'/'.$TotalMaxMarks;
								?>
							 </th>
							 <th>
								<?php
								echo $tot_show_grade=calculate_secondary_grade($tot_avg); ?>
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
						?>
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
		 $TotalGetMarks=0;
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
	





<!------		Co-Scholastic	------------>
	<table width="100%"  cellspacing="0px" cellpadding="0px" border="1">
		<tr>
            <th style="height:30px"  width="24%"  bgcolor="CCFFCC" >Co-Scholastic Area</th>
            <?php 
			$no=0;
			$subjct=mysql_query("select * from `subject` where `grade`='G' ");
			while($ftc_subject=mysql_fetch_array($subjct))
			{
				$subject_id=$ftc_subject['id'];
				$subject1=$ftc_subject['subject'];
				
				$st=mysql_query("select * from `exam_mapping` where `class_id`='$class_id' && `section_id`='$section_id' && `term_id`='$term_id'");
				$ft=mysql_fetch_array($st);
				$grd_exam_category_type_id=$ft['exam_category_type_id'];
				
				$marks=mysql_query("select * from `student_marks`  where `scholar_no`= '$scholar_no' && `subject_id`='$subject_id' && `term_id`='$exam_name' && `master_exam_type_id`='$grd_exam_category_type_id'");
				$ftc_marks=mysql_fetch_array($marks);
				$grd_marks=$ftc_marks['marks'];
				$no++;
				if($subject_id=='10'){ break; }
				?>
            <th <?php if($no==1){?>width="18%"<?php } else if($no==2){?> width="20%" <?php } else {?> width="15%" <?php }?>  bgcolor="#E0A366" ><?php echo $subject1; ?></th>
			<td align="center"><?php if(!empty($grd_marks)){ echo $grd_marks;}?></td>
			<?php } ?>
        </tr>
    </table>
	
	
	
	
	
	<table width="100%" height="340px" cellspacing="0px" cellpadding="0px" border="1" id="sample_1">
		<tbody>
			<tr class="header_font" bgcolor="CCFFCC">
				 <th height="25" colspan="102" style="margin-left:5px">
					PERFORMANCE - APPRAISAL AND FEEDBACK
				 </th>
			</tr>
			<!--tr class="header_font" bgcolor="#E0A366">
				<th height="30px" colspan="3"><?php //echo $term; ?></th>
			</tr-->
			<tr class="header_font" bgcolor="#E0A366">
				<th height="33" width="40%" style="margin-left:5px">Activities</th>
				<th>Grade</th>
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
				<td style="text-align:left;">&nbsp;&nbsp;&nbsp;
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
            <td width="50%"> 
                    <table height="350" width="100%" border="1" cellspacing="0" cellpadding="0" style="text-align:center">
                    <tr bgcolor="CCFFCC"   >
                    	<th colspan="3" scope="col" height="28"><b>Nine Point Grading Scale</b></th>
                    </tr>
                    <tr style="font-size:16px" bgcolor="#E0A366" >
                        <td width="202"  height="25" ><strong>Marks Range</strong></td>
                        <td width="124"><strong>Grade</strong></td>
                        <td width="184"><strong>Grade Point</strong></td>
                    </tr>
                    <tr>
                        <td >91-100</td>
                        <td>A1</td>
                        <td>10.0</td>
                    </tr>
                    <tr>
                        <td>81-90</td>
                        <td>A2</td>
                        <td>9.0</td>                    
                    </tr>
                    <tr>
                        <td>71-80</td>
                        <td>B1</td>
                        <td>8.0</td>
                    </tr>
                    <tr>
                        <td >61-70</td>
                        <td>B2</td>
                        <td>7.0</td>
                    </tr>
                    <tr>
                        <td >51-60</td>
                        <td>C1</td>
                        <td>6.0</td>
                    </tr>
                    <tr>
                        <td >45-50</td>
                        <td>C2</td>
                        <td>5.0</td>  
                    </tr>
                    <tr>
                        <td >40-44</td>
                        <td>D</td>
                        <td>4.0</td>
                    </tr>
                    <tr>
                        <td >21-39</td>
                        <td>E1</td>
                        <td>3.0</td>
                    </tr>
                    <tr>
                        <td>00-20</td>
                        <td>E2</td>
                        <td>2.0</td>
                    </tr>
                    </table>
            </td>
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
            <td width="50%"> 
           		<table height="350" width="100%" border="1" cellspacing="0" cellpadding="0" style="text-align:center" >
                    <tr bgcolor="CCFFCC">
                    	<th colspan="2" scope="col" height="28" >Session Report (Signature)</th>
                    </tr>
                    <tr style="font-size:16px" bgcolor="#E0A366"   >
                        <td width="135" style="text-align:left; padding-left:15px" height="25"><strong>Designation</strong></td>
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
                        <td style="text-align:left; padding-left:15px">Attendance</td>
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

		     <tr height="80px">
                        <td style="text-align:center; font-size:18px">Principal<br>( Seal & Signature )</td>
                   		<td>&nbsp;</td>
                    </tr>
           	 	</table>
           </td>
        </tr>
        </table>
</div>
	<?php }?>
</body>
</html>
