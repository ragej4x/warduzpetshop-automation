<div id="ph-value"></div>
<div id="temp-value"></div>

<script>



    const response = 9; 
    const data = 6;



    if (data > 8){
        document.getElementById('ph-value').style.color = 'red';
    }else{
        document.getElementById('ph-value').style.color = 'black';
    }

    document.getElementById('ph-value').textContent = `${data} pH`;

</script>