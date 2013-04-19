<?php
/*
 LICENSE

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License (GPL)
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 To read the license please visit http://www.gnu.org/copyleft/gpl.html
 ----------------------------------------------------------------------
 Original Author of file: Lerider Steven
 Purpose of file: Manage generic actions.
 ----------------------------------------------------------------------
*/
/**
 * 
 * @copyright    AlternC-Team 2002-2013 http://alternc.org/
 */
class m_action {
  /*---------------------------------------------------------------------------*/
  /** Constructor
  */
  function m_action() {
  }
  /*
  * function to set the cration of a file 
  */
  function create_file($file,$content,$user="root") {
    return $this->set('create_file',$user, array('file'=>$file,'content'=>$content));
  }
  /*
  * function to set the cration of a file 
  */
  function create_dir($dir,$user="root") {
    return $this->set('create_dir',$user, array('dir'=>$dir));
  }
  /*
  * function to delete file / folder
  */
  function del($dir) {
    return $this->set('delete',$user, array('dir'=>$dir));
  }
  /*
  * function returning the first not locked line of the action table 
  */
  function move($src,$dest) {
    return $this->set('move',$user, array('src'=>$src, 'dst'=>$dst));
  }
  /*
  * function archiving a directory ( upon account deletion )
  */
  function archive($archive) {
    global $cuid;
    //utiliser la function move après avoir construit le chemin
    $today=getdate();
    $dest=$BACKUP_DIR.'/'.$today["year"].'-'.$today["mon"].'/'.$uidlogin.'/';
    $this->move($archive,$dest);
  }
  /*
  *function inserting the action in the sql table 
  */
  function set($type,$user,$parameters) {
    global $db;
    
    $serialized=serialize($parameters);
    switch($type){
    case 'create_file':
      //do some shit
      $db->query("insert into actions values ('','CREATE_FILE','$serialized','','','','$user','');"); 
    case 'create_dir':
     //do more shit
      $db->query("insert into actions values ('','CREATE_DIR','$serialized','','','','$user','');"); 
    case 'move':
     //do more shit
      $db->query("insert into actions values ('','MOVE','$serialized','','','','$user','');"); 
    case 'delete':
     //do more shit
      $db->query("insert into actions values ('','DELETE','$serialized','','','','$user','');"); 
    case 'archive':
     //do more shit
      $db->query("insert into actions values ('','ARCHIVE','$serialized','','','','$user','');"); 
    default:
      return false;
    }
  }

  /*
  * function returning the first not locked line of the action table 
  */
  function get_action() {
    global $db;

    $tab=array();
    $db->query('select * from (select * form actions where end="" and begin="" order by id) x group by id');
    if ($db->next_record()){
      $tab[]=$db->Record;
      return $tab;
    }else
      return false;
  }
  /*
  * function locking an entry while it is being executed by the action script
  */
  function begin($id) {
    global $db;
    $db->query("update actions set begin=".date()." where id=$id ");
    return true;
  }
  /*
  * function locking an entry while it is being executed by the action script
  */
  function finish($id) {
    global $db;
    $db->query("update actions set end=".date()." where id=$id ");
    return true;
  }
  /*
  * function locking an entry while it is being executed by the action script
  */
  function cancel($id) {
    global $db;
    $db->query("update actions set end=".date()." where id=$id ");
    return true;
  }

} /* Class action */

?>
