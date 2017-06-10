let hackathon = [], project = [], exhibition = [], guests = [];

$('input').bind("propertychange change keyup input paste", ()=>{
    let pass = $('#admin_pass').val();
    if(pass === "irasecretpass") {
        $('.hidden').removeClass('hidden');
        document.cookie = "logged=true;";
        $('.auth').hide();
        init();
    }
});
let init_disabled = () => {
    $.ajax({
        url: '../getParticipants.php',
        type: 'GET'
    }).done((data) =>{
       let participants = JSON.parse(data);
        hackathon = participants.filter((p) => {
            return p['hackathon_key'] !== '0' && p['hackathon_key'] !== '-1' && p['hackathon_key'] !== '' && p['hackathon_key'] !== null;
        });
       $('#hackathon').DataTable(hackathon)
    });
};
let init = () =>{
    $.ajax({
        url: '../getParticipants.php',
        type: 'GET'
    })
        .done((data)=> {

            let participants = JSON.parse(data);
            hackathon = participants.filter((p) => {
                return p['hackathon_key'] !== '0' && p['hackathon_key'] !== '-1' && p['hackathon_key'] !== '' && p['hackathon_key'] !== null;
            });
	    project = participants.filter((p) => {
                return p['project_title'] !== '' && p['project_title'] !== null;
            });
    	    exhibition = participants.filter((p) => {
                return p['company'] !== '' && p['company'] !== null;
            });
            guests = participants.filter((p) => {
                return p['guest'] === 'true' || p['guest'] === '1';
            });

            go();
        });
};
let go = () => {
    $("#hackathon").tabulator({
        fitColumns: true,
        //  responsiveLayout:true,
        columns: [
            {title: "Name", field: "name_surname", sortable: true, frozen:true},
            {title: "Age", field: "age", sortable: true, sorter: "number"},
            {title: "Phone", field: "phone", sortable: false},
            {title: "Email", field: "email", formatter: 'email'},
            {title: "City", field: "city", sortable: true},
            {title: "Team", field: "team_name", sortable: true},
            {title: "Created at", field: "created_at", sortable: true}

        ]
    }).tabulator("setData", hackathon);

    $("#projects").tabulator({
        fitColumns: true,
        columns: [
            {title: "Name", field: "name_surname", sortable: true, frozen:true},
            {title: "Age", field: "age", sortable: true, sorter: "number"},
            {title: "Phone", field: "phone", sortable: false},
            {title: "Email", field: "email", formatter: 'email'},
            {title: "City", field: "city", sortable: true},
            {title: "Project", field: "project_title", sortable: true},
            {title: "Project Details", field: "project_description", sortable: false},
            {title: "Category", field: "project_category", sortable: true},
            {title: "Created at", field: "created_at", sortable: true}
        ]
    }).tabulator("setData", project);

    $("#exhibition").tabulator({
        fitColumns: true,
        columns: [
            {title: "Name", field: "name_surname", sortable: true, frozen:true},
            {title: "Phone", field: "phone", sortable: false},
            {title: "Email", field: "email", formatter: 'email'},
            {title: "City", field: "city", sortable: true},
            {title: "Company", field: "company", sortable: true},
            {title: "Product", field: "exhibition_product", sortable: true},
            {title: "Interactive Element", field: "interactive_element", sortable: false},
            {title: "Additional needs", field: "exhibition_needs", sortable: false},
            {title: "Created at", field: "created_at", sortable: true}
        ],
    }).tabulator("setData", exhibition);

    $("#guests").tabulator({
        fitColumns: true,
        columns: [
            {title: "Name", field: "name_surname", sortable: true, frozen:true},
            {title: "Age", field: "age", sortable: true, sorter: "number"},
            {title: "Phone", field: "phone", sortable: false},
            {title: "Email", field: "email", formatter: 'email'},
            {title: "City", field: "city", sortable: true},
            {title: "Created at", field: "created_at", sortable: true}
        ],
    }).tabulator("setData", guests);
};
init();
$(document).ready(()=>{
    let cookie_logged = document.cookie.indexOf('logged') !== -1;
    if(cookie_logged){
        $('.hidden').removeClass('hidden');
        $('.auth').hide();
        init();
    }else{
        $('.auth input').focus();
    }
});