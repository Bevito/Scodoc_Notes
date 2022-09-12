<?php 
	$path = realpath($_SERVER['DOCUMENT_ROOT'] . '/..');
	require_once "$path/includes/default_config.php";
	require_once "$path/includes/analytics.class.php";
	Analytics::add('gestionAbsences');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Justif. absences</title>
    <style>
        <?php include $_SERVER['DOCUMENT_ROOT']."/assets/header.css"?>
        header{
            position: sticky;
            left:0;
            top:0;
        }      
        main{
            text-align: center;
        }
        @media screen and (max-width: 1120px){
            html, body{
                overflow: auto;
                height: 100vh;
            }
            main{
                max-width: initial;
            }
        }
        .contenu{
            opacity: 0.5;
            pointer-events: none;
            user-select: none;
			position: relative;
        }
        .ready{
            opacity: initial;
            pointer-events: initial;
        }
        .message{
            position: fixed;
            bottom: 100%;
            left: 50%;
            z-index: 10;
            padding: 20px;
            border-radius: 0 0 10px 10px;
            background: #90c;
            color: #FFF;
            font-size: 24px;
            animation: message 3s;
            transform: translate(-50%, 0);
        }
        @keyframes message{
            20%, 80%{transform: translate(-50%, 100%)}
        }
        .capitalize{
            text-transform: capitalize;
        }
/**********************/
/*   Zones de choix   */
/**********************/
        .zone{
            background: #FFF;
            padding: 8px;
            margin-bottom: 8px;
            border-radius: 4px;
            border: 1px solid #CCC;
        }
		select{
			font-size: 21px;
			padding: 10px;
			margin: 5px auto;
			background: #09c;
			color: #FFF;
			border: none;
			border-radius: 10px;
            max-width: 100%;
            display: table;
            box-shadow: 0 2px 2px #888;
		}
        .highlight{
            animation: pioupiou 0.4s infinite ease-in alternate;
        }
        @keyframes pioupiou{
            0%{
                box-shadow: 0 0 4px 0px orange;
            }
            100%{
                box-shadow: 0 0 4px 2px orange;
            }
        }

/*******************************/
/* Listes étudiants */
/*******************************/
		.contenu>button{
			position: absolute;
			top: -9px;
			right: 0;
			border: 1px solid #CCC;
			border-radius: 4px;
			padding: 8px 16px;
			background: #FFF;
			cursor: pointer;
		}
		.contenu>button:hover{
			background: #0C9;
			color: #FFF;
		}
        .flex{
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .groupes{
            margin-bottom: 10px;
			display: flex;
            justify-content: center;
        }
        .groupe{
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 10px;
            margin: 2px;
            background: #09C;
            color: #FFF;
            border-radius: 8px;
        }
        @media screen and (max-width: 1120px){
            .flex{
                flex-direction: column-reverse;
            }
            .groupes{
                margin-top: 32px;
                flex-wrap: wrap;
            }

        }
        .selected{
            opacity: 0.5;
        }
        .hide{
            display: none !important;
        }

/*****************************/
/* Zone absences */
/*****************************/
        .date{
            position: sticky;
            top: 0;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
            background: #0C9;
            color: #FFF;
            border-radius: 10px;
            border: none;
        }
        .date>svg{
            cursor: pointer;
        }
        #actualDate{
            padding: 4px 0;
        }
		.etudiants{
            counter-reset: cpt;
			display: flex;
			justify-content: center;
			flex-direction: column;
			gap: 1px;
        }
		.semaine{
			position: sticky;
			top: 40px;
			z-index: 1;
			margin-top: 4px;
		}
		.etudiants>.semaine>div{
			cursor: initial !important;
			transition-delay: .035s;
			border-color: #09c;
            width: initial;
		}
		.semaine>div:nth-child(1){
			grid-column: 2;
		}
		.etudiants>div:hover:not(.semaine)>div:nth-child(1), .showDay{
			background: #c09 !important;
			color: #FFF;
			transition-delay: 0s !important;
			border-color: #c09 !important;
		}
		.etudiants>div{
			display: grid;
			grid-template-columns: 300px repeat(6, 144px);
			gap: 5px;
		}
		.etudiants>div>div{
			border-radius: 10px;
            border: 1px solid #eee;
            background: #FFF; 
            cursor: pointer;
		}
		.etudiants>div>.dayStudent{
			position: relative;
			overflow: hidden;
			cursor: initial;
		}
		.etudiants>div>.dayStudent:hover{
			border: 1px solid #777;
		}
		.etudiants>div>.dayStudent>div{
			position: absolute;
			top: 0;
			bottom: 0;
			border-radius: 10px;
			border: 1px solid #FFF;
		}

		.etudiants>div>.dayStudent>div:not([data-statut=present]){
			cursor: pointer;
		}
		.etudiants>div>.dayStudent>div:not([data-statut=present]):hover{
			border: 2px solid #0C9;
		}

        .etudiants .btnAbsences{
            position: relative;
            text-align: left;
            padding: 10px 20px;
			border-color: #09c;
            width: initial;
            justify-self: initial;
        }
        .btnAbsences>div:nth-child(1){
            display: flex;
            gap:5px;
			overflow: hidden;
        }
        .btnAbsences>div:nth-child(1)::before{
            counter-increment: cpt;
            content: counter(cpt) " ";
            display: inline-block;
        }
        .btnAbsences>div:nth-child(1)>:last-child{
            margin-left: auto;
        }

		.btnAbsences>img{
			position: absolute;
			bottom: 100%;
			right: 0;
			pointer-events:none;
			background: #FFF;
			border-radius: 16px;
			border: 1px solid #c09;
			display: none;
		}
		.btnAbsences:hover{
			z-index: 1;
		}
		.btnAbsences:hover>img{
			display: block;
		}

        @media screen and (max-width: 1120px){
            .zone{
                position: sticky;
                left: 0;
            }
            .date, .groupes{
                position: sticky;
                left: 10px;
                width: calc(100vw - 28px);
            }
            .etudiants .btnAbsences{
                padding: 8px 16px;
                position: sticky;
                left: 0;
            }
            .btnAbsences>div:nth-child(1)::before{
                display: none;
            }
        }
		[data-statut=present]{
            background: #00bcd4;
        }
        [data-statut=absent]{
            background: #ec7068;
        }
		[data-statut=retard]{
            background: #f3a027;
        }
		[data-justifie=true]{
            background: #0C9;
        }
    </style>
    <meta name=description content="Gestion des absences de l'<?php echo $Config->nom_IUT; ?>">
</head>
<body>
    <?php 
        $h1 = 'Stats / Justif';
        include $_SERVER['DOCUMENT_ROOT']."/assets/header.php";
    ?>
    <main>
		<p>
			Bonjour <span class=nom></span>.
		</p>

		<div class="zone">
			<select id=departement class=highlight onchange="clearStorage(['semestre', 'matiere']);selectDepartment(this.value)">
				<option value="" disabled selected hidden>Choisir un département</option>
				<?php
					include "$path/includes/scodoc.class.php";
					$Scodoc = new Scodoc();
					$listDepartement = $Scodoc->getDepartmentsList();
					foreach($listDepartement as $departement){
						echo "<option value=$departement>$departement</option>";
					}
				?>
			</select>

			<select id=semestre onchange="selectSemester(this.value)" disabled>
				<option value="" disabled selected hidden>Choisir un semestre</option>
			</select>
		</div>

        <div class=contenu></div>
        <div class=wait></div>
        
    </main>

    <div class=auth>
        <!-- Site en maintenance -->
        Authentification en cours ...
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx-populate/1.21.0/xlsx-populate.min.js"></script>
    <script>
		<?php
            include "$path/includes/clientIO.php";
		?>  
        document.querySelector("#gestion").classList.add("navActif");

/*********************************************/
/* Vérifie l'identité de la personne et son statut
/*********************************************/		
        var session = "";
        var statutSession = "";
        async function checkStatut(){
            let data = await fetchData("donnéesAuthentification");
            session = data.session;
            document.querySelector(".nom").innerText = data.name;
            let auth = document.querySelector(".auth");
            auth.style.opacity = "0";
            auth.style.pointerEvents = "none";
            statutSession = data.statut;

            if(data.statut >= PERSONNEL){
                document.querySelector("body").classList.add('personnel');
				if(data.statut >= ADMINISTRATEUR){
					document.querySelector("#admin").style.display = "inherit";
				}
                /* Gestion du storage remettre le même état au retour */
                let departement = localStorage.getItem("departement");
                if(departement){
                    document.querySelector("#departement").value = departement;
                    selectDepartment(departement);
                }

			} else {
				document.querySelector(".contenu").innerHTML = "Ce contenu est uniquement accessible pour des personnels de l'IUT. ";
			}
        }
/*********************************************/
/* Récupère et traite les listes d'étudiants du département */
/*********************************************/		
        var departement = "";
        var semestre = "";
        var dataEtudiants;
        var depAdmins = [];

        async function selectDepartment(dep){
            departement = dep;
			let data = await fetchData("semestresDépartement&dep="+departement);
			
			let select = document.querySelector("#semestre");
			select.innerHTML = `<option value="" disabled selected hidden>Choisir un semestre</option>`;
			data.forEach(function(semestre){
				let option = document.createElement("option");
				option.value = semestre.id;
				option.innerText = `${semestre.titre_long} - Semestre ${semestre.num}`;
				select.appendChild(option);
            });
            document.querySelector("#departement").classList.remove("highlight");
            document.querySelector(".contenu").classList.remove("ready");
            select.disabled = false;
            select.classList.add("highlight");

            /* Gestion du storage remettre le même état au retour */
            localStorage.setItem('departement', departement);

            let semestre = localStorage.getItem("semestre");
            if(semestre){
                document.querySelector("#semestre").value = semestre;
				if(document.querySelector("#semestre").value){
					selectSemester(semestre);
				} else {
					document.querySelector("#semestre").value = "";
				}
            }
            depAdmins = await fetchData("listeAdministrateurs&dep=" + departement);
		}
		
		async function selectSemester(sem){
            semestre = sem;

            document.querySelector("#semestre").classList.remove("highlight");
            document.querySelector(".contenu").classList.add("ready");

            getStudentsListes();
            /* Gestion du storage remettre le même état au retour */
            localStorage.setItem('semestre', semestre);
		}

        async function getStudentsListes(){
            dataEtudiants = await fetchData(`listeEtudiantsSemestre&dep=${departement}&semestre=${semestre}&absences=true`);
            document.querySelector(".contenu").innerHTML = createSemester(dataEtudiants);

            changeDate(0);
        }

        function clearStorage(keys){
            keys.forEach(function(key){
                localStorage.removeItem(key);
            });
        }

        function createSemester(liste){
			var output = (statutSession >= ADMINISTRATEUR)?`<button onclick="createSemesterReport()">Rapport d'absences</button>`:"";

            var groupes = "";
            if(liste.groupes.length > 1){
                liste.groupes.forEach(groupe=>{
                    groupes += `<div class=groupe data-groupe="${groupe}" onclick="hideGroupe(this)">${groupe}</div>`;
                })
            }
            output += `
				<div class=flex>
					<div>
						<div class=groupes>${groupes}</div>
						<div class=date>

							<svg onclick=changeDate(-1) xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>

							<div id=actualDate></div>

							<svg onclick=changeDate(1) xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>

						</div>
						<div class=etudiants>${createStudents(liste.etudiants)}</div>
					</div>
				</div>
            `;

            return output;
        }

        function createStudents(etudiants){
			let output = `
				<div class=semaine>
					<div>Lundi</div>
					<div>Mardi</div>
					<div>Mercredi</div>
					<div>Jeudi</div>
					<div>Vendredi</div>
					<div>Samedi</div>
				</div>
			`;

			let calFrame = `
				<div class=dayStudent data-day=0 onmouseenter="showDay(this)" onmouseleave="stopShowDay(this)"></div>
				<div class=dayStudent data-day=1 onmouseenter="showDay(this)" onmouseleave="stopShowDay(this)"></div>
				<div class=dayStudent data-day=2 onmouseenter="showDay(this)" onmouseleave="stopShowDay(this)"></div>
				<div class=dayStudent data-day=3 onmouseenter="showDay(this)" onmouseleave="stopShowDay(this)"></div>
				<div class=dayStudent data-day=4 onmouseenter="showDay(this)" onmouseleave="stopShowDay(this)"></div>
				<div class=dayStudent data-day=5 onmouseenter="showDay(this)" onmouseleave="stopShowDay(this)"></div>
			`;

			etudiants.forEach(etudiant=>{
				output += `
					<div>
						<div class="btnAbsences ${etudiant.groupe?.replace(/ |\./g, "")}" 
							data-nom="${etudiant.nom}" 
							data-prenom="${etudiant.prenom}" 
							data-groupe="${etudiant.groupe}"
							data-nip="${etudiant.nip}"
                            title="${etudiant.groupe} - Télécharger le rapport d'absence de l'étudiant"
                            onclick="createStudentReport(this)">
								<img src="../services/data.php?q=getStudentPic&nip=${etudiant.nip}" alt="etudiant" width="250" height="350">
								<div>
									<b>${etudiant.nom}</b>
									<span>${etudiant.prenom}</span>
								</div>
						</div>
						${calFrame}
					</div>
				`;
			})

			return output;
		}

		function showDay(obj){
			document.querySelector(".semaine").children[obj.dataset.day].classList.add("showDay");
		}
		function stopShowDay(obj){
			document.querySelector(".semaine").children[obj.dataset.day].classList.remove("showDay");
		}

		function hideGroupe(obj, num){
			let nbSelected = obj.parentElement.querySelectorAll(".selected").length;
			let nbBtn = obj.parentElement.children.length;
			
			if(nbSelected == 0){
				Array.from(obj.parentElement.children).forEach(e=>{
					e.classList.toggle("selected");
				})
			}
			obj.classList.toggle("selected");

			nbSelected = obj.parentElement.querySelectorAll(".selected").length;
			if(nbSelected == nbBtn){
				Array.from(obj.parentElement.children).forEach(e=>{
					e.classList.toggle("selected");
				})
			}

			
			let groupesSelected = [];
			obj.parentElement.querySelectorAll(":not(.selected)").forEach(e=>{
				groupesSelected.push(e.dataset.groupe);
			})

			document.querySelectorAll(".btnAbsences").forEach(e=>{
				if(groupesSelected.includes(e.dataset.groupe)){
					e.parentElement.classList.remove("hide")
				} else {
					e.parentElement.classList.add("hide")
				}	
			})
        }

/*************************************/
/* Gestion des dates et des absences */
/*************************************/
        var dateLundi = new Date();
        let dayNumber = dateLundi.getDay();
        dayNumber -= dayNumber == 0 ? -6:1;
        dateLundi.setDate(dateLundi.getDate() - dayNumber);

        function changeDate(num){
            dateLundi.setDate(dateLundi.getDate() + num * 7);
			let dateSamedi = new Date(dateLundi);
            dateSamedi.setDate(dateLundi.getDate() + 5);
            document.querySelector("#actualDate").innerText = `Du lundi ${dateLundi.toLocaleDateString()} au samedi ${dateSamedi.toLocaleDateString()}`;

            let jours = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
            let dateTemp = new Date(dateLundi);
            let semaine = document.querySelector(".semaine");
            for(let i=0 ; i<6 ; i++){
                semaine.children[i].innerText = `${jours[i+1]} ${dateTemp.getDate()}`;
                dateTemp.setDate(dateTemp.getDate() + 1);
            }

            showAbsences();
        }

		function showAbsences(){
            document.querySelectorAll(".dayStudent").forEach(e=>e.innerHTML = "");

            Object.entries(dataEtudiants.absences).forEach(([etudiant, datesAbsences])=>{
				for(let i=0 ; i<6 ; i++){
					let date = new Date(dateLundi);
                    date.setDate(date.getDate() + i);
                    let absencesJour = datesAbsences[ISODate(date)];

					absencesJour?.forEach(absence=>{
						let heureDebut = <?php echo $Config->absence_heureDebut; ?>;
						let heureFin = <?php echo $Config->absence_heureFin; ?>;

						let posiDebut = (absence.debut - heureDebut) / (heureFin - heureDebut) * 100;
						let tailleDuree = (absence.fin - absence.debut) / (heureFin - heureDebut) * 100;
						
						let ligne = document.querySelector(`[data-nip="${etudiant}"]`);

						if(ligne){
							ligne.parentElement.children[i+1].innerHTML += `
								<div 
									style="left:${posiDebut}%;width:${tailleDuree}%" 
									data-statut="${absence.statut}" 
									data-justifie="${absence.justifie}" 
									data-debut="${absence.debut}"
									data-fin="${absence.fin}"
									title="${absence.debut}h - ${absence.fin}h - ${absence.enseignant}"
									onclick="${(absence.statut != "present") ? "justify(this)":""}">
								</div>`;
						}
					})
				}
            })
        }

		async function justify(obj){
			if(statutSession < ADMINISTRATEUR){
				return message("Seul un administrateur peut justifier une absence");
			}
            if(depAdmins.indexOf(session) == -1 && statutSession < SUPERADMINISTRATEUR){
              //  return message("Vous ne pouvez pas modifier une absence d'un autre département");
            }

            if(obj.dataset.justifie == "false"){
				obj.setAttribute("data-justifie", "true")
            } else {
                obj.setAttribute("data-justifie", "false")
            }

            let date = new Date(dateLundi);
            date.setDate(dateLundi.getDate() + parseInt(obj.parentElement.dataset.day));
            date = ISODate(date);
           
            let response = await fetchData("setJustifie" + 
                "&semestre=" + semestre +
                "&etudiant=" + obj.parentElement.parentElement.children[0].dataset.nip +
                "&date=" + date +
                "&debut=" + obj.dataset.debut +
                "&justifie=" + obj.dataset.justifie
            );

            if(response.result != "OK"){
                displayError("Il y a un problème - l'absence n'a pas été enregistrée.");
				return;
            }

            dataEtudiants.absences[obj.parentElement.parentElement.children[0].dataset.nip][date].map(e=>{
				if(e.debut == obj.dataset.debut){
					return (obj.dataset.justifie == "true") ? true : false;
				}
			})
        }

		function ISODate(date){
            // Date ISO du type : 2021-01-28T15:38:04.622Z -- on ne récupère que AAAA-MM-JJ.
            return date.toISOString().split("T")[0];
        }

        function message(msg){
            var div = document.createElement("div");
            div.className = "message";
            div.innerHTML = msg;
            document.querySelector("body").appendChild(div);
            setTimeout(()=>{
                div.remove();
            }, 3000);
        }
/***************************/
/* Gestion des rapports d'absence
/***************************/
        function getExcel(obj, xlsxName) {
			return fetch(xlsxName)
				.then(function (response) { return response.blob() })
				.then(function (blob) {
					return blob;
				})
		}

		function saveFile(name, workbook) {
			workbook.outputAsync()
			.then(function (blob) {
				var url = window.URL.createObjectURL(blob);
				var a = document.createElement("a");
				document.body.appendChild(a);
				a.href = url;
				a.download = name + ".xlsx";
				a.click();
				window.URL.revokeObjectURL(url);
				document.body.removeChild(a);
			});
		}
        function createStudentReport(obj){
            let absences = dataEtudiants.absences[obj.dataset.email];
            let sem = document.querySelector("#semestre");
            let semestreTxt = sem.options[sem.selectedIndex].text;

			XlsxPopulate.fromBlankAsync()
            .then(workbook => {
                let now = new Date();
                now = now.toLocaleDateString();
                const sheet = workbook.sheet(0);
                sheet.name("Absences");

				sheet.column("A").width(11);
				sheet.column("C").width(22);
				sheet.column("D").width(22);

                sheet.cell("A1").value("Rapport d'absences").style("fontSize", 18);
                sheet.cell("A2").value(`${semestreTxt}`).style("fontSize", 24);
                sheet.cell("A3").value(`${obj.dataset.prenom} ${obj.dataset.nom}`).style("fontSize", 24);
                sheet.cell("A4").value(`${obj.dataset.num}`);
                sheet.cell("A5").value(`${now}`);
                
				var i = 7;
                sheet.cell("A"+i).value("Absences injustifiées").style("fontSize", 18);
				i++;

				sheet.cell("A"+i).value([[
						"Date",
						"Créneau",
						"Enseignant",
						"Matière",
						"UE"
					]]).style({
						bold: true,
						fill: "0099CC",
						fontColor: "FFFFFF"
					});
				i++;

				var total = {};
				Object.entries(dataEtudiants.absences[obj.dataset.email] || {}).forEach(([date, listeCreneaux])=>{
					Object.entries(listeCreneaux).forEach(([creneau, data])=>{
						if(data.statut == "absent"){
							sheet.cell("A"+i).value(date.split("-").reverse().join("/"));
							sheet.cell("B"+i).value(creneau.replace(",", "-"));
							sheet.cell("C"+i).value(mailToTxt(data.enseignant));
							sheet.cell("D"+i).value(data.matiereComplet);
							sheet.cell("E"+i).value(data.UE);

							total[data.UE] = (total[data.UE] + 1) || 1;
							i++;
						}
					})
				})

				Object.entries(total).forEach(([ue, nombre])=>{
					sheet.cell("A"+i).value(`Nombre d'absences injustifiées ${ue} : ${nombre}`);
					sheet.range("A"+i+":E"+i).style({
						bold: true,
						fill: "00CC99",
						fontColor: "FFFFFF"
					});
					i++;
				})

				i++;
 				sheet.cell("A"+i).value("Absences justifiées").style("fontSize", 18);
				i++;

                sheet.cell("A"+i).value([[
						"Date",
						"Créneau",
						"Enseignant",
						"Matière",
						"UE"
					]]).style({
						bold: true,
						fill: "0099CC",
						fontColor: "FFFFFF"
					});
				i++;
				
				total = {};
				Object.entries(dataEtudiants.absences[obj.dataset.email] || {}).forEach(([date, listeCreneaux])=>{
					Object.entries(listeCreneaux).forEach(([creneau, data])=>{
						if(data.statut == "absent excuse"){
							sheet.cell("A"+i).value(date.split("-").reverse().join("/"));
							sheet.cell("B"+i).value(creneau.replace(",", "-"));
							sheet.cell("C"+i).value(mailToTxt(data.enseignant));
							sheet.cell("D"+i).value(data.matiereComplet);
							sheet.cell("E"+i).value(data.UE);

							total[data.UE] = (total[data.UE] + 1) || 1;
							i++;
						}
					})
				})

				Object.entries(total).forEach(([ue, nombre])=>{
					sheet.cell("A"+i).value(`Nombre d'absences justifiées ${ue} : ${nombre}`);
					sheet.range("A"+i+":E"+i).style({
						bold: true,
						fill: "00CC99",
						fontColor: "FFFFFF"
					});
					i++;
				})

                saveFile("Absences - " + semestreTxt + " " + obj.dataset.email.split("@")[0].replaceAll(".", " ").toUpperCase(), workbook);
            });
        }

		function createSemesterReport(){
            let sem = document.querySelector("#semestre");
            let semestreTxt = sem.options[sem.selectedIndex].text;

			XlsxPopulate.fromBlankAsync()
            .then(workbook => {
                let now = new Date();
                now = now.toLocaleDateString();
                const sheet = workbook.sheet(0);
                sheet.name("Absences");

				sheet.column("A").width(11);
				sheet.column("B").width(11);

                sheet.cell("A1").value("Rapport d'absences").style("fontSize", 18);
                sheet.cell("A2").value(`${semestreTxt}`).style("fontSize", 24);
                sheet.cell("A3").value(`${now}`);
                
				var i = 5;

				var colonne = 'D';
				UE.forEach(e=>{
					sheet.cell(colonne+i).value(e.UE).style({
						bold: true,
						fill: "00CC99",
						fontColor: "FFFFFF"
					});
					sheet.cell(colonne+(i+1)).value([["Justifié", "Injustifié"]]).style({
						bold: true,
						fill: "0099CC",
						fontColor: "FFFFFF"
					});
					colonne = changeChar(colonne, 2);
				})
				sheet.cell(colonne+i).value([
					["Totaux", ""],
					["Justifié", "Injustifié"]
				]).style({
					bold: true,
					fill: "9900CC",
					fontColor: "FFFFFF"
				});

				i++;
				sheet.cell("A"+i).value([[
						"Nom",
						"Prénom",
						"Numéro"
					]]).style({
						bold: true,
						fill: "0099CC",
						fontColor: "FFFFFF"
					});
				i++;

				var absences = nbAbsences();

				dataEtudiants.etudiants.forEach(etudiant=>{
					sheet.cell("A"+i).value([[
						etudiant.nom,
						etudiant.prenom,
						etudiant.num_etudiant
					]]);
					colonne = "D";
					totJust = 0;
					totInjust = 0;
					UE.forEach(e=>{
						sheet.cell(colonne+i).value([[
							absences[etudiant.email][e.UE].justifie,
							absences[etudiant.email][e.UE].injustifie
						]]);
						totJust += absences[etudiant.email][e.UE].justifie;
						totInjust += absences[etudiant.email][e.UE].injustifie;
						colonne = changeChar(colonne, 2);
					})
					sheet.cell(colonne+i).value([[
						totJust,
						totInjust
					]]);
					i++;
				})

                saveFile("Absences - " + semestreTxt, workbook);
            });
        }

		function changeChar(char, nb){
			return String.fromCharCode(char.charCodeAt(0) + nb);
		}

		function nbAbsences(){
			/*Réponse sous la forme d'une strucutre :
				{
					"mail etudiant": {
						"UE1": {
							"jusitifie": 22,
							"injustifie" : 33
						},
						"UE2": {
							"jusitifie": 22,
							"injustifie" : 33
						}
					}
				}
			*/
			var output = {};
			dataEtudiants.etudiants.forEach(etudiant=>{
				if(!output[etudiant.email]){
					output[etudiant.email] = {};
					UE.forEach(ue=>{
						output[etudiant.email][ue.UE] = {
							justifie: 0,
							injustifie: 0
						}
					})
				}

				Object.values(dataEtudiants.absences[etudiant.email] || {}).forEach(dateAbsence=>{
					Object.values(dateAbsence).forEach(creneau=>{
						if(creneau.UE != "pas besoin"){
							if(creneau.statut == "absent"){
								output[etudiant.email][creneau.UE].injustifie += 1;
							} else {
								output[etudiant.email][creneau.UE].justifie += 1;
							}
						}
					})
				})
			})
			return output;
		}

		function mailToTxt(mail){
			let tab = mail.split("@")[0].split(".");
			return tab[0].charAt(0).toUpperCase() + tab[0].slice(1) + " " + tab[1].toUpperCase();
		}

/***************************/
/* C'est parti !
/***************************/
        checkStatut();
    </script>
    <?php 
        include "$path/config/analytics.php";
    ?>
</body>
</html>
