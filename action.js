document.getElementById('btnopenclose').addEventListener('click', functionChangeCloseOpen)
document.getElementById('buttonChange').addEventListener('click', functionChaneInput)
function functionChangeCloseOpen(){
    var component_content  = document.getElementById('component_content');
    if(component_content.style.display === 'block'){
        component_content.style.display = 'none'
        document.getElementById('btnopenclose').style.left = '0px'
        document.getElementById('icon-btn-change').className = ' fas fa-caret-right';
        document.getElementById('viewDiv').style.width = "100%"
    }
    else{
        component_content.style.display = 'block';
        document.getElementById('btnopenclose').style.left = '30%'
        document.getElementById('icon-btn-change').className = ' fas fa-caret-left';
        document.getElementById('viewDiv').style.width = "70%"
    }

}

function functionChaneInput(){
    var hospital = document.getElementById('hospital');
    var home = document.getElementById('home');
    if(hospital.name === 'hospital'){
        hospital.setAttribute('name', 'home')
        hospital.setAttribute('placeholder', 'Nhập địa chỉ của bạn')
        home.setAttribute('name', 'hospital')
        home.setAttribute('placeholder', 'Nhập tên bệnh viện')
    }
    else{
        home.setAttribute('name', 'home')
        home.setAttribute('placeholder', 'Nhập địa chỉ đến')
        hospital.setAttribute('name', 'hospital')
        hospital.setAttribute('placeholder', 'Nhập tên bệnh viện')
    }
}