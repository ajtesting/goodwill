<script type="text/javascript">
<?php echo $js ?> 
 
function ofc_ready(){
// alert('ofc_ready');
}

<?php foreach ($fields as $field){ ?>
function get_data_<?php echo $field->id ?>(){return JSON.stringify(data_<?php echo $field->id ?>);}
var data_<?php echo $field->id ?>=<?php echo $data[$field->id] ?>;
<?php } ?>

function get_data_time(){return JSON.stringify(data_time);}
var data_time=<?php echo $data['time'] ?>;
</script>