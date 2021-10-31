<?php 
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "address_data";

$building="";
          $street= $landmark = $lcality = $vtc = $district = $state = " ";

    $conn = new mysqli($servername,$username,$password,$dbname);

    if($conn -> connect_error)
      {
       die("Connection Failed : " .$conn -> connect_error);
      }

      $sql = "SELECT * FROM `address_adhar` ORDER BY `address_adhar`.`id` DESC";

      $result = $conn->query($sql);
      if ($result -> num_rows == 0 )
      {
        echo "0 results";        
      }
?>
<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
table, th, td {  border:2px solid black;border-collapse: collapse;}

td {text-align: center;padding: 8px;}
tr:nth-child(even) { background-color: pink;}
body{ background-color: greenyellow; }
</style>
</head>
<body>  




<table style="width:100%">

   

   <tr>

      <th colspan="9" padding="5px" style="background-color: #D6EEEE"><h2>Address_Database</h2></th>
  
   </tr>

   <tr style="background-color: #ADD8E6">

    <th><h4>House No.</h4></th>

    <th><h4>Street</h4></th>

    <th><h4>Landmark</h4></th>

    <th><h4>Locality</h4></th>

    <th><h4>V/T/C</h4></th>

    <th><h4>District</h4></th>

    <th><h4>State</h4></th>

    <th style="background-color: white"><h4>Old_Address</h4></th>

    <th style="background-color: yellow"><h4>New_Address</h4></th>


   </tr>

    <?php
      

      $result =$conn->query("SELECT * FROM address_adhar ");
      
      function clutter($add)
        {
          if($add==null)
          {
            $add_explode_by_space = [];
            return $add_explode_by_space;
          } 

         else
        {
          $add = strtoupper($add);
          $add_explode_by_coma = explode(",",$add);
          $add_explode_by_coma_count = count($add_explode_by_coma);
          $add_explode_by_space = explode(" ",$add_explode_by_coma[0]);
          if($add_explode_by_coma_count > 1)
          {
            for($x=1;$x<$add_explode_by_coma_count;$x++)
           { 
            $z=explode(" ",$add_explode_by_coma[$x]);
            $add_explode_by_space = array_merge($add_explode_by_space,$z);
           }
          }
           
          return $add_explode_by_space; 
        }
      }

        function check($chk1,$chk2)
        {
           $f= array("0");
           $chk3 = implode(" ",$chk1);
           $chk1 = explode(" ",$chk3);
           $x_limit = count($chk1);
           $y_limit = count($chk2);
           for ($y = 0; $y < $y_limit ; $y++)
           {    
               for ($x =  0; $x < $x_limit ; $x++)
               {
                 
                 if(isset($chk1[$x]) && isset($chk2[$y]))
                  {
                 $sim = similar_text($chk1[$x], $chk2[$y],$perc); 
                 


                 if($perc > 99)
                 {
                   array_push($f,$x); 
                 }
            } } 
            if( count($f) != 1 )
             {
               
               for($z=1;$z < count($f);$z++ )
                   unset($chk1[$f[$z]]);  
             
             }
           }
         return $chk1;
        }

       






      while ($row=$result->fetch_assoc())
      {
        
    ?>
        
    <tr>
      <td><center><?= $row['building']; ?></center></td>
      <td><center><?= $row['street']; ?></center></td>
      <td><center><?= $row['locality']; ?></center></td>
      <td><center><?= $row['landmark']; ?></center></td>
      <td><center><?= $row['vtc']; ?></center></td>
      <td><center><?= $row['district']; ?></center></td>
      <td><center><?= $row['state']; ?></center></td>
      <td><center><?php echo $row['building']." , ".$row['street']." , ".$row['locality']." , ".$row['landmark']." , ".$row['vtc']." , ".$row['district']." , ".$row['state']; ?></center></td>
    
    <?php
         //Converting every line to array
        $building_array = clutter($row['building']);
        $street_array = clutter($row['street']);
        $landmark_array = clutter($row['landmark']);
        $locality_array = clutter($row['locality']);
        $vtc_array = clutter($row['vtc']);
        $district_array = clutter($row['district']);
        $state_array = clutter($row['state']);
        
        //Checking common words from one line to another 
        
        $building_array = check($building_array,$street_array);
        
        $building_array = check($building_array,$landmark_array);
      
        $building_array = check($building_array,$locality_array);
        
        $street_array = check($street_array,$landmark_array);
        
        $street_array = check($street_array,$locality_array);
        
        $landmark_array = check($locality_array,$landmark_array);
        

        //Checking for common Vtc and district
        if(count($vtc_array)==count($district_array))
           {
             $f=0;
               for($x=0 ; $x < count($vtc_array);$x++)
               { 
                 $sim=similar_text($vtc_array[$x],$district_array[$x],$perc);
                 

                 if($perc > 99 )
                      $f++;
               }

              if($f == count($district_array))
              {
              
              $building_array = check($building_array,$district_array);
              $street_array  = check($street_array,$district_array);
              $landmark_array = check($landmark_array,$district_array);
              $locality_array = check($locality_array,$district_array);
              
           
           
           
           $building_without_space  = implode("",$building_array);
           $street_without_space    = implode("",$street_array);
           $landmark_without_space  = implode("",$landmark_array);
           $locality_without_space  = implode("",$locality_array);
           $district_without_space  = implode("",$district_array);
            
            if($building_without_space == $street_without_space)
               $building_array=[];
             if($building_without_space == $landmark_without_space)
               $building_array=[];
             if($building_without_space == $locality_without_space)
               $building_array=[];
             if($building_without_space == $district_without_space)
               $building_array=[];
             if($street_without_space == $landmark_without_space)
               $street_array=[];
             if($street_without_space == $locality_without_space)
               $street_array=[];
             if($street_without_space == $district_without_space)
               $street_array=[];
             if($landmark_without_space == $locality_without_space)
               $landmark_array=[];
            if($landmark_without_space == $district_without_space)
               $landmark_array=[];
            if($locality_without_space == $district_without_space )
               $locality_array=[];

            //$building_array = clutter($building_array);
            //$street_array = clutter($street_array);
            //$landmark_array = clutter($landmark_array);
            //$locality_array = clutter($locality_array);

             }

           if ($building_array==null) 
           {
              if($street_array==null)
              {
                if($landmark_array==null)
                {
                  if($locality_array==null)
                  {
                    $a = array(",");
                    $address = [];
                    $address = array_merge($district_array,$a,$state_array);
                  }
                  else
                  {
                    
                    $a = array(",");
                    $address = [];
                    $address = array_merge($locality_array,$a,$district_array,$a,$state_array);
                  }
                }
                else
                {
                  $a = array(",");
                  $address = [];
                  $address = array_merge($landmark_array,$a,$locality_array,$a,$district_array,$a,$state_array);
                }
              }
              else
              {
                $a = array(",");
                $address = [];
                $address = array_merge($street_array,$a,$landmark_array,$a,$locality_array,$a,$district_array,$a,$state_array);
              }
           }
           else
           {
              $a = array(",");
              $address = [];
              $address = array_merge($building_array,$a,$street_array,$a,$landmark_array,$a,$locality_array,$a,$district_array,$a,$state_array); 
           }





           }
           //Checking if vtc and district are not same
         else
         {
           $building_array = check($building_array,$vtc_array);
           $street_array  = check($street_array,$vtc_array);
           $landmark_array = check($landmark_array,$vtc_array);
           $locality_array = check($locality_array,$vtc_array);
           $building_array = check($building_array,$district_array);
           $street_array  = check($street_array,$district_array);
           $landmark_array = check($landmark_array,$district_array);
           $locality_array = check($locality_array,$district_array);
          
           $building_without_space  = implode("",$building_array);
           $street_without_space    = implode("",$street_array);
           $landmark_without_space  = implode("",$landmark_array);
           $locality_without_space  = implode("",$locality_array);
           $vtc_without_space       = implode("",$vtc_array);
           $district_without_space  = implode("",$district_array);
            
            if($building_without_space == $street_without_space)
               $building_array=[];
             if($building_without_space == $landmark_without_space)
               $building_array=[];
             if($building_without_space == $locality_without_space)
               $building_array=[];
             if($building_without_space == $vtc_without_space)
               $building_array=[];
             if($building_without_space == $district_without_space)
               $building_array=[];
             if($street_without_space == $landmark_without_space)
               $street_array=[];
             if($street_without_space == $locality_without_space)
               $street_array=[];
             if($street_without_space == $vtc_without_space)
               $street_array=[];
             if($street_without_space == $district_without_space)
               $street_array=[];
             if($landmark_without_space == $locality_without_space)
               $landmark_array=[];
             if($landmark_without_space == $vtc_without_space)
               $landmark_array=[];
            if($landmark_without_space == $district_without_space)
               $landmark_array=[];
            if($locality_without_space == $vtc_without_space)
               $locality_array=[];

           // $building_array = clutter($building_array);
            //$street_array = clutter($street_array);
            //$landmark_array = clutter($landmark_array);
            //$locality_array = clutter($locality_array);    

           if ($building_array==null) 
           {
             if($street_array==null)
             {
                if($landmark_array==null)
                {
                   if($locality_array==null)
                   {
                     $a = array(",");
                     $address = [];
                     $address = array_merge($vtc_array,$a,$district_array,$a,$state_array);
                   }
                   else
                   {
                    $a = array(",");
                    $address = [];
                    $address = array_merge($locality_array,$a,$vtc_array,$a,$district_array,$a,$state_array) ;
                   }
                }
                else
                {
                  $a = array(",");
                  $address = [];
                  $address = array_merge($landmark_array,$a,$locality_array,$a,$vtc_array,$a,$district_array,$a,$state_array) ;
                }
             }
             else
             {
               $a = array(",");
               $address = [];
               $address = array_merge($street_array,$a,$landmark_array,$a,$locality_array,$a,$vtc_array,$a,$district_array,$a,$state_array) ;
             }

           }
           else
           {
             $a = array(",");
             $address = [];
             $address = array_merge($building_array,$a,$street_array,$a,$landmark_array,$a,$locality_array,$a,$vtc_array,$a,$district_array,$a,$state_array) ; 
           }







         
         
         










         } 
         







         

         





         //Removing null values from array of common address
         //if($building_array==null)
           //unset(address[1]);

       
        
         $address_merge = implode(" ",$address);
         $address_1 = [];
         $address_1 = explode(" ",$address_merge);
         
         /*
         $b=count($address_1);
         
         $c=0;
         while($c<$b)

        {
        
           if(empty($address_1[$c]) == 1 )
             {  
              unset($address_1[$c]); 
              

              //$d=$c+1;
              //unset($address[$d]);
             } 
         

           $c++;
         }
         */
         $d=0;
           
          $b=count($address_1);
          $b--;
         
         
         //Removing extra comma from common address
        while($d<$b)
         {
           
              if($address_1[$d] == $address_1[$d+1] && $address_1[$d]== ",")
              {  
              unset($address_1[$d]); 
              //$d=$c+1;
              //unset($address[$d]);
             } 
           
           $d++;
         
         }
         //Finally printing the resultent address

         $address_2=implode(" ",$address_1);
         $address_1=explode(" ",$address_2);
         if($address_1[0]==$a)
  	    {
 	     unset($address_1[0]);
	    } 




    ?>
      <td><center><?php echo ucwords(strtolower(implode(" ",$address_1))); ?></center></td>
    </tr>
    

<?php
      }
    ?>

</table>
</body>
</html>