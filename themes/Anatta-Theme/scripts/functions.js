//function for changing classname for navigation
function hidedivider(id){
  		document.getElementById(id).className="divider-hide";
  	}
  	  	function showdivider(id){
  		document.getElementById(id).className="show-hide";
  	}

//function for checking ajax status
function check_ajax_support()
{
var xmlHttp=null;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
   }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttp;
}

//function for getting filter by for custom taxonomy
function filterby_page(val, current_page)
{
	xmlHttp=check_ajax_support();
	if (xmlHttp==null)
	{
		alert ("Your browser does not support AJAX!");
		return;
	}
	physician = document.getElementById('filterbyp').value;
	country = document.getElementById('filterbyc').value;
	speciality = document.getElementById('filterbys').value;
	medical = document.getElementById('filterbym').value;
	hospital = document.getElementById('filterbyh').value;

	if(current_page) 
	{ 
		document.getElementById('current_page').value=current_page; 
	}
	else 
	{ 
		current_page=document.getElementById('current_page').value; 
	}
	 
	
	var url=templateURL+"/ajax/ajax_clientportal_listing.php";
	url=url+"?physician="+physician;
	url=url+"&country="+country;
	url=url+"&speciality="+speciality;
	url=url+"&medical="+medical;
	url=url+"&hospital="+hospital;
	url=url+"&current_page="+current_page;
	url=url+"&sid="+Math.random();
	//alert(url);
	xmlHttp.open("GET",url,true);
    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttp.setRequestHeader("Content-length",url.length);
    xmlHttp.setRequestHeader("Connection", "close");
    xmlHttp.send(url);
	xmlHttp.onreadystatechange=function()
	{
		if(xmlHttp.readyState==4)
		{
			if(xmlHttp.responseText != '')
			{
				//alert(xmlHttp.responseText);
				document.getElementById('main_listing_div').innerHTML='';
				document.getElementById('main_listing_div').innerHTML=xmlHttp.responseText;
			}
		}
	}
	return false;
}

