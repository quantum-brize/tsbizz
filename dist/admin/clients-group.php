<?php 
$u = decode($_REQUEST['u']);

if($_REQUEST['u']==''){
$u=$_SESSION['userid'];
}
$abcd=GetPageRecord('*','userMaster','id="'.decode($_REQUEST['g']).'"'); 
$result=mysqli_fetch_array($abcd); 
?>
 <script src="tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
selector: "#emailsignature",
themes: "modern",
plugins: [
"advlist autolink lists link image charmap print preview anchor",
"searchreplace visualblocks code fullscreen"
],
toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>

<div class="wrapper">
<div class="container-fluid">
<div class="main-content">

                <div class="page-content">

      <div class="newboxheading"><div class="newhead">Clients Group<div class="newoptionmenu">
  <form  action=""  class="newsearchsecform"  style="left:114px;"  method="get" enctype="multipart/form-data">	
								  <input type="text" name="keyword" class="form-control newsearchsec"  placeholder="Search by name"  value="<?php echo $_REQUEST['keyword']; ?>" style="margin-top: 3px;">
								  <input name="ga" type="hidden" value="<?php echo $_REQUEST['ga']; ?>" />
								  </form>
 </div>
 </div>     
 
     
</div>
                    
                    <!-- start page title -->
                     
              
                        <div class=" ">
                        <div class="col-md-12 col-xl-12" style="padding-top:32px;">
						<div class="card" >
                            <div class="card-body" style="padding:0px;"> 
                                      
							  
                                        <table class="table table-hover mb-0">

                                            <thead>
                                                <tr>
                                                  <th>Name</th>
                                                    <th>Description</th>
                                                    <th><div align="center">Subscribers</div></th>
                                                    <th width="1%">Status</th>
                                                    <th width="15%" align="left">By</th>
                                                    <th width="12%">Date</th>
                                                    <th width="1%">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
$totalno='1';
$totalmail='0';
$select='';
$where='';
$rs=''; 
$select='*'; 
$wheremain=''; 
$where=' where    (name like "%'.$_REQUEST['keyword'].'%" or description like "%'.$_REQUEST['keyword'].'%" ) order by id desc'; 
$limit=clean($_GET['records']);
$page=clean($_GET['page']); 
$sNo=1; 
$targetpage='display.html?ga='.$_REQUEST['ga'].'&keyword='.$_REQUEST['keyword'].'&'; 
$rs=GetRecordList('*','clientGroupMaster','  '.$where.'  ','25',$page,$targetpage);

$totalentry=$rs[1];

$paging=$rs[2];  
while($rest=mysqli_fetch_array($rs[0])){ 
?>

<tr>
  <td><?php echo stripslashes($rest['name']); ?></td>
  <td><?php echo stripslashes($rest['description']); ?></td>
<td> <div align="center"><a href="display.html?ga=clients-group-contacts&g=<?php echo encode($rest['id']); ?>"><i class="fa fa-user" aria-hidden="true"></i> <?php
 $a=GetPageRecord('count(id) as totalclients','clientGroupContacts',' groupId="'.$rest['id'].'" ');  
$result=mysqli_fetch_array($a); echo $result['totalclients'];
?></a>
</div></td>
<td width="1%"><?php echo newstatusbadges($rest['status']); ?></td>
<td width="15%" align="left"><?php echo addbynewbadges($rest['addedBy']); ?></td>
<td width="12%"><?php if(date('d-m-Y', strtotime($rest['dateAdded']))=='01-01-1970'){ echo '-'; } else {  echo date('d-m-Y', strtotime($rest['dateAdded'])); } ?></td>
<td width="1%">
<a class="dropdown-item neweditpan" onclick="loadpop('Edit Client Group',this,'600px')" data-toggle="modal" data-target=".bs-example-modal-center" popaction="action=addclientgroup&id=<?php echo encode($rest['id']); ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a> </td>
</tr>


<?php $totalno++; } ?>
                                            </tbody>
                                        </table>
                           
									 <?php if($totalno==1){ ?>
						   <div style="text-align:center; padding:40px 0px; font-size:14px; color:#999999;">No Supplier</div>
						   <?php } else { ?>
								<div class="mt-3 pageingouter">	
										<div style="float: left; font-size: 13px; padding: 7px 11px; border: 1px solid #ededed; background-color: #fff; color: #000;">Total Records: <strong><?php echo $totalentry; ?></strong></div>
											<div class="pagingnumbers"><?php echo $paging; ?></div>
											
							  </div>
										  
										<?php } ?>
						  </div>
								 
                             
</div>
                             

                        </div>

                         
						
						
						
						 
                     

             </div><!--end col-->

            <!-- end row -->

    </div>

        <!-- End Page-content -->

         
    </div>
	</div>	</div>