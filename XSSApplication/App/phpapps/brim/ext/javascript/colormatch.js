var hs=new Object();
var rg=new Object();

function sample_go()
{ 
	document.getElementById('sample').style.backgroundColor = document.getElementById('col7').value;
	document.getElementById('sampleh1').style.backgroundColor = document.getElementById('col0').value;
	document.getElementById('sampleh1').style.color = document.getElementById('col2').value;
	document.getElementById('sampleh1').style.borderColor = document.getElementById('col6').value;
	document.getElementById('sampleh2').style.backgroundColor = document.getElementById('col3').value;
	document.getElementById('sampleh2').style.color = document.getElementById('col5').value;
	document.getElementById('sampleh2').style.borderColor = document.getElementById('col6').value;
	document.getElementById('samplep').style.backgroundColor = document.getElementById('col7').value;
	document.getElementById('samplep').style.color = document.getElementById('col8').value;
	document.getElementById('samplep').style.borderColor = document.getElementById('col6').value;
	document.getElementById('samplelink').style.color = document.getElementById('col1').value;
	document.getElementById('samplehover').style.color = document.getElementById('col4').value;

}
function htmlgo()
 { 
	k=document.getElementById('html').value;
	j = k.replace('#','');
	red = j.substr(0,2);
	gre = j.substr(2,2);
	blu = j.substr(4,2);
	r.setValue(parseInt(red,16));
	g.setValue(parseInt(gre,16));
	b.setValue(parseInt(blu,16));
	
}

function c2r(d)
{
	k=document.getElementById(d).style.backgroundColor;
	j=(k.substr(4,k.indexOf(")")-4)).split(",");
	r.setValue(j[0]);
	g.setValue(j[1]);
	b.setValue(j[2]);
}
function load_theme()
{
	sel = document.getElementById("coltheme");
	var d = sel.options[sel.selectedIndex].value;
	j=d.split(",");
	r.setValue(j[0]);
	g.setValue(j[1]);
	b.setValue(j[2]);
}

function ud(x,c)
{
	document.getElementById("sw"+x).style.backgroundColor="rgb("+c.r+","+c.g+","+c.b+")";
	document.getElementById("hc"+x).innerHTML="#"+rg2html(c) + "<br />R: "+c.r+"<br />G: "+c.g+"<br />B: "+c.b;
	document.getElementById("col"+x).value="#"+rg2html(c)
/*
	if(x == 0) 
		{
		document.getElementById("bookmark").value="http://color.twysted.net/?color="+rg2html(c);
	}
*/
}
function rg2html(z)
{
	return d2h(z.r)+d2h(z.g)+d2h(z.b);
}
function d2h(d)
{
	hch="0123456789abcdef";
	a=d%16;
	q=(d-a)/16;
	return hch.charAt(q)+hch.charAt(a);
}
function h2r(hs)
{
	var rg=new Object();
	if(hs.s==0)
	{
		rg.r=rg.g=rg.b=Math.round(hs.v*2.55);
		return rg;
	}
	hs.s=hs.s/100;
	hs.v=hs.v/100;
	hs.h/=60;
	i=Math.floor(hs.h);
	f=hs.h-i;
	p=hs.v*(1-hs.s);
	q=hs.v*(1-hs.s*f);
	t=hs.v*(1-hs.s*(1-f));
	switch(i)
	{
		case 0:rg.r=hs.v;
		rg.g=t;
		rg.b=p;
		break;
		case 1:rg.r=q;
		rg.g=hs.v;
		rg.b=p;
		break;
		case 2:rg.r=p;
		rg.g=hs.v;
		rg.b=t;
		break;
		case 3:rg.r=p;
		rg.g=q;
		rg.b=hs.v;
		break;
		case 4:rg.r=t;
		rg.g=p;
		rg.b=hs.v;
		break;
		default:rg.r=hs.v;
		rg.g=p;
		rg.b=q;
	}
	rg.r=Math.round(rg.r*255);
	rg.g=Math.round(rg.g*255);
	rg.b=Math.round(rg.b*255);
	return rg;
}
function rc(x,m)
{
	if(x>m)
	{
		return m
	}
	if(x<0)
	{
		return 0
	}
	else
	{
		return x
	}
}
function rg2hs(rg)
{
	m=rg.r;
	if(rg.g<m)
	{
		m=rg.g
	};
	if(rg.b<m)
	{
		m=rg.b
	};
	v=rg.r;
	if(rg.g>v)
	{
		v=rg.g
	};
	if(rg.b>v)
	{
		v=rg.b
	};
	value=100*v/255;
	delta=v-m;
	if(v==0.0)
	{
		hs.s=0
	}
	else
	{
		hs.s=100*delta/v
	};
	if(hs.s==0)
	{
		hs.h=0
	}
	else
	{
		if(rg.r==v)
		{
			hs.h=60.0*(rg.g-rg.b)/delta
		}
		else if(rg.g==v)
		{
			hs.h=120.0+60.0*(rg.b-rg.r)/delta
		}
		else if(rg.b=v)
		{
			hs.h=240.0+60.0*(rg.r-rg.g)/delta
		}
		if(hs.h<0.0)
		{
			hs.h=hs.h+360.0
		}
	}
	hs.v=Math.round(value);
	hs.h=Math.round(hs.h);
	hs.s=Math.round(hs.s);
	return(true);
}
function dom()
{
	z=new Object();
	y=new Object();
	yx=new Object();
	p=new Object();
	pr=new Object();
	p.s=y.s=hs.s;
	p.h=y.h=hs.h;
	if(hs.v>70)
	{
		y.v=hs.v-30
		p.v=y.v +15
		z=h2r(p);
		ud("1",z);
		

	}
	else
	{
		y.v=hs.v+30
		p.v=y.v-15
		z=h2r(p);
		ud("1",z);
		
	};
	z=h2r(y);
	ud("2",z);
	if((hs.h>=0)&&(hs.h<30))
	{
		pr.h=yx.h=y.h=hs.h+20;
		pr.s=yx.s=y.s=hs.s;
		y.v=hs.v;
		if(hs.v>70)
		{
			yx.v=hs.v-30
			pr.v = yx.v +15
		}
		else
		{
			yx.v=hs.v+30
			pr.v = yx.v -15
		}
	}
	if((hs.h>=30)&&(hs.h<60))
	{
		pr.h=yx.h=y.h=hs.h+150;
		y.s=rc(hs.s-30,100);
		y.v=rc(hs.v-20,100);
		pr.s=yx.s=rc(hs.s-70,100);
		yx.v=rc(hs.v+20,100);
		pr.v=hs.v
	}
	if((hs.h>=60)&&(hs.h<180))
	{
		pr.h=yx.h=y.h=hs.h-40;
		pr.s=y.s=yx.s=hs.s;
		y.v=hs.v;
		if(hs.v>70)
		{
			yx.v=hs.v-30
			pr.v = yx.v +15
		}
		else
		{
			yx.v=hs.v+30
			pr.v = yx.v -15
		}
	}
	if((hs.h>=180)&&(hs.h<220))
	{
		pr.h=yx.h=hs.h-170;
		y.h=hs.h-160;
		pr.s=yx.s=y.s=hs.s;
		y.v=hs.v;
		if(hs.v>70)
		{
			yx.v=hs.v-30
			pr.v = yx.v +15
		}
		else
		{
			yx.v=hs.v+30
			pr.v = yx.v -15
		}
	}
	if((hs.h>=220)&&(hs.h<300))
	{
		pr.h=yx.h=y.h=hs.h;
		pr.s=yx.s=y.s=rc(hs.s-60,100);
		y.v=hs.v;
		if(hs.v>70)
		{
			yx.v=hs.v-30
			pr.v = yx.v +15
		}
		else
		{
			yx.v=hs.v+30
			pr.v = yx.v -15
		}
	}
	if(hs.h>=300)
	{
		if(hs.s>50)
		{
			pr.s=y.s=yx.s=hs.s-40
		}
		else
		{
			pr.s=y.s=yx.s=hs.s+40
		}
		pr.h=yx.h=y.h=(hs.h+20)%360;
		y.v=hs.v;
		if(hs.v>70)
		{
			yx.v=hs.v-30
			pr.v = yx.v +15
		}
		else
		{
			yx.v=hs.v+30
			pr.v = yx.v -15
		}
	}
	z=h2r(y);
	ud("3",z);
	z=h2r(yx);
	ud("5",z);
	y.h=0;
	y.s=0;
	y.v=100-hs.v;
	z=h2r(y);
	ud("6",z);
	y.h=0;
	y.s=0;
	y.v=hs.v;
	z=h2r(y);
	ud("7",z);
	z=h2r(pr);
	ud("4",z);
	if(hs.v >= 50) { pr.v = 0 } else { pr.v = 100 } 
	pr.h=pr.s=0;
	z=h2r(pr);
	ud("8",z);
	sample_go();
}
