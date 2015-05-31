var current = 1;
$(document).ready(function(){$('#num').val(0);});

                function toggle(dir){
                        $('img').each(function(){
                                this.style.border = '0';
                        });     
                        if(dir == 1)
                                current = (current + 1) % 9;
                        else
                                current = (current - 1) % 9;
			
			if(current < 0)
				current += 9;
			$('#num').val(current + 1);
		//	current = current == 0 ? 9 : current;
			console.log(current);
                        $('img')[current].style.border = '3px solid red';
                }
