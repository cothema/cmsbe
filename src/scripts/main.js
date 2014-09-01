$(function() {
    $( "#left-resizable" ).resizable({ handles: "e" });
    
    $( window ).resize(function() {
        $( "#left-also-top" ).width($( "#left-resizable" ).width()-20);
        
        $( ".crop-left" ).css("margin-left",$( "#left-resizable" ).width());
    });
  });