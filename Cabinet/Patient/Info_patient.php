<?php session_start(); ?>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Information Personnelle de Patient </h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>


<div id="AffichP">
    <?php include "Affichage_patient.php";?>

</div>


<script >
    var patientId = '<?php echo $_SESSION["patientId"]; ?>';
    $('#Recherche').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
            CIN=$("#Recherche").val();
            $("#AffichP").load("Patient/Affichage_patient.php?CIN="+CIN+"&Id_pat="+patientId);
        }
    });


</script>
