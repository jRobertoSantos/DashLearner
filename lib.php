<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

function block_dashlearner_get_grades_by_user ($course_id, $user_id) {
    global $DB;

    $sql = "select from_unixtime(timecreated, \"%Y-%m-%d\")  as x, max(grade) as y
            from {assign_grades} where userid = ?  and grade > 0 and
            from_unixtime(timecreated, \"%Y-%m-%d\") between \"2019-02-18\" and \"2019-05-30\"
            group by from_unixtime(timecreated, \"%Y-%m-%d\");";
    $params = array($user_id);
    $result = $DB->get_records_sql($sql, $params);

    return $result;
}

function block_dashlearner_get_forum_posts_by_user ($course_id, $user_id) {
    global $DB;

    $sql = "select count(fp.message) as qty
        from {forum_discussions} as fd, 
			 {forum_posts} as fp
	    where fd.id = fp.discussion and  fd.course= ? and fp.userid = ?";
    $params = array($course_id, $user_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_forum_posts_by_course ($course_id) {
    global $DB;

    $sql = "select count(fp.message)/count(distinct fp.id) as  qty
        from {forum_discussions} as fd, 
			 {forum_posts} as fp
	    where fd.id = fp.discussion and  fd.course= ?";
    $params = array($course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_assign_submission_by_user ($course_id, $user_id) {
    global $DB;

    $sql = "select count(name)  as qty
     from {assign} as a,
	 {assign_submission} as asu 
     where a.course=? and a.id=asu.assignment 
       and asu.status = 'submitted' and asu.userid = ?";
    $params = array($course_id, $user_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_assign_submission_by_course ($course_id) {
    global $DB;

    $sql = "select count(name)/count(distinct asu.userid)  as qty
     from {assign} as a,
	 {assign_submission} as asu 
     where a.course=? and a.id=asu.assignment 
       and asu.status = 'submitted'";
    $params = array($course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_quiz_by_user ($course_id, $user_id) {
    global $DB;

    $sql = "select count(q.name) as qty
                  from {quiz} as q, 
                   {quiz_attempts} as qa
               where q.id=qa.quiz and qa.state='finished' 
                     and q.course = ?
                    and qa.userid = ?";
    $params = array($course_id, $user_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_quiz_by_course ($course_id) {
    global $DB;

    $sql = "select count(q.name)/count(distinct qa.userid) as qty
                  from {quiz} as q, 
                   {quiz_attempts} as qa
               where q.id=qa.quiz and qa.state='finished' 
                     and q.course = ?";
        $params = array($course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_resource_by_user ($course_id, $user_id) {
    global $DB;

    $sql = "select count(log.eventname)  as qty
               from {logstore_standard_log} as log
              inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
           where log.contextlevel in (50,70) 
             and log.anonymous=0 
             and log.crud='r' 
             and  log.component in ('mod_page','mod_resource','mod_url') 
             and  log.courseid= ?           
             and log.userid = ?";
    $params = array($course_id, $user_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_resource_by_course ($course_id) {
    global $DB;

    $sql = "select count(log.eventname)/count(distinct log.userid)  as qty
               from {logstore_standard_log} as log
               inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
           where log.contextlevel in (50,70) 
             and log.anonymous=0 
             and log.crud='r' 
             and  log.component in ('mod_page','mod_resource','mod_url') 
             and  log.courseid= ?";
    $params = array($course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_activities_by_user ($course_id, $user_id) {
    global $DB;

    $sql = "select count(log.eventname) as qty
           from {logstore_standard_log} as log
           inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
               where log.contextlevel in (50,70) and log.anonymous=0 and log.crud='r' and
                  log.component in ('mod_forum','mod_assign','mod_chat','mod_glossary','mod_quiz','mod_wiki') 
               and  log.courseid= ?           
               and log.userid = ?";
    $params = array($course_id, $user_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_activities_by_course ($course_id) {
    global $DB;

    $sql = "select count(log.eventname)/count(distinct log.userid) as qty
           from {logstore_standard_log} as log
           inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
               where log.contextlevel in (50,70) and log.anonymous=0 and log.crud='r' and
                  log.component in ('mod_forum','mod_assign','mod_chat','mod_glossary','mod_quiz','mod_wiki') 
               and  log.courseid= ?";
    $params = array($course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_access_by_user ($course_id, $user_id) {
    global $DB;

    $sql = "select count(log.eventname) as qty
             from {logstore_standard_log} as log
             inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
              where log.contextlevel in (50,70) and log.anonymous=0 and log.crud='r' and
                log.component in ('core') and log.eventname like '%course_viewed%' 
               and  log.courseid= ?           
               and log.userid = ?";

    $params = array($course_id, $user_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_access_by_course ($course_id) {
    global $DB;

    $sql = "select count(log.eventname)/count(distinct log.userid) as qty
             from {logstore_standard_log} as log
             inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
              where log.contextlevel in (50,70) and log.anonymous=0 and log.crud='r' and
                log.component in ('core') and log.eventname like '%course_viewed%' 
               and  log.courseid= ?";
    $params = array($course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_course_modules($course_id)
{
    global $DB;

    $sql = "SELECT cm.module as module, md.name as name
                FROM {course_modules} as cm
                LEFT JOIN {modules} as md ON cm.module = md.id
                WHERE cm.course = ? 
                GROUP BY cm.module, md.name";
    $params = array($course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_course_modules_activities($course_id)
{
    global $DB;

    $sql = "SELECT cm.module as module, md.name as name
                FROM {course_modules} as cm
                LEFT JOIN {modules} as md ON cm.module = md.id
                WHERE cm.course = ? 
                AND md.name in ('assign', 'feedback', 'quiz','vpl')
                GROUP BY cm.module, md.name";
    $params = array($course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_course_modules_resources($course_id)
{
    global $DB;

    $sql = "SELECT cm.module as module, md.name as name
                FROM {course_modules} as cm
                LEFT JOIN {modules} as md ON cm.module = md.id
                WHERE cm.course = ? 
                AND md.name in ('resource','page','url', 'chat','lesson','wiki','forum')
                GROUP BY cm.module, md.name";
    $params = array($course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}



function block_dashlearner_get_assign_by_user ($course_id, $user_id) {

    global $DB;
     $sql = "select asn.id as id, concat('[',cs.name,']: ',asn.name) as name,  
       (select count(distinct x.userid)
             from {user_enrolments} ue
            inner join {role_assignments} ra on ra.userid = ue.userid
            inner join {context} ctx on ctx.id = ra.contextid
            inner join  {assign_submission} x on  x.latest = 1  
                and x.userid = ue.userid  and  x.status = 'submitted' 
            where x.assignment = asn.id and ra.roleid = 5 and ctx.id = ra.contextid and x.timecreated <= asn.duedate
                and  ctx.instanceid = ? and ue.status = 0 and x.userid = ?  and ctx.contextlevel = 50)  as flg_submissao_prazo,
        (select count(distinct x.userid)
              from {user_enrolments} ue
            inner join {role_assignments} ra on ra.userid = ue.userid
            inner join {context} ctx on ctx.id = ra.contextid
            inner join  {assign_submission} x on  x.latest = 1  
                and x.userid = ue.userid  and  x.status = 'submitted' 
            where x.assignment = asn.id and ra.roleid = 5 and ctx.id = ra.contextid and x.timecreated > asn.duedate
             and  ctx.instanceid = ? and ue.status = 0 and x.userid = ? and ctx.contextlevel = 50 )  as flg_submissao_fora_prazo,     
         (select count(distinct ue.userid) 
            from {user_enrolments}  ue
                inner join {role_assignments} ra on ra.userid = ue.userid
                inner join {context} ctx on ctx.id = ra.contextid
                inner join  {assign_submission} x on  x.latest = 1
                    and x.userid = ue.userid  and  x.status = 'submitted' 
            where x.assignment = asn.id and ra.roleid = 5 
                and ctx.id = ra.contextid and x.timecreated <= asn.duedate
                and  ctx.instanceid = ? and ue.status = 0 and ctx.contextlevel = 50)  as qtd_submissoes_prazo,
        (select count(distinct ue.userid) 
            from {user_enrolments} ue
                inner join {role_assignments} ra on ra.userid = ue.userid
                inner join {context} ctx on ctx.id = ra.contextid
                inner join {assign_submission} x on  x.latest = 1 and x.userid = ue.userid  and  x.status = 'submitted' 
            where x.assignment = asn.id and ra.roleid = 5 
                and ctx.id = ra.contextid and x.timecreated > asn.duedate
                and  ctx.instanceid = ? and ue.status = 0 and ctx.contextlevel = 50)  as qtd_submissoes_atraso,
        (select count(distinct ue.userid) 
            from {user_enrolments} ue
                inner join {role_assignments} ra on ra.userid = ue.userid
                inner join {context} ctx on ctx.id = ra.contextid
                where  ra.roleid = 5 and ctx.id = ra.contextid
                    and  ctx.instanceid = ? and ctx.contextlevel = 50) as qtd_matriculados
      from {assign} asn
         inner join {course_modules} cm on cm.instance = asn.id
         inner join {course_sections} cs on cs.id = cm.section
      where asn.course = ? and cm.visible =1 
      group by asn.name
      order by asn.id";

    $params = array($course_id, $user_id, $course_id, $user_id, $course_id, $course_id, $course_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_quiz ($course_id, $user_id) {
    global $DB;
    
    $sql = "select q.id as id, concat('[',cs.name,']: ',q.name) as name,  
       (select count(distinct x.userid)
             from {user_enrolments} ue
            inner join {role_assignments}  ra on ra.userid = ue.userid
            inner join {context}  ctx on ctx.id = ra.contextid
            inner join  {quiz_attempts} x ON  x.userid = ue.userid  and  x.state = 'finished' 
            where x.quiz = q.id and ra.roleid = 5 and ctx.id = ra.contextid 
                and  ctx.instanceid = ? and ue.status = 0 and x.userid = ? and ctx.contextlevel = 50 )  as flg_submissao_prazo,
         (select count(distinct x.userid)
             from {user_enrolments} ue
            inner join {role_assignments}  ra on ra.userid = ue.userid
            inner join {context}  ctx on ctx.id = ra.contextid
            inner join  {quiz_attempts} x ON  x.userid = ue.userid  and  x.state = 'finished' 
            where x.quiz = q.id and ra.roleid = 5 and ctx.id = ra.contextid 
                and  ctx.instanceid = ? and ue.status = 0 and ctx.contextlevel = 50)  as qtd_usuarios_acessos,
         (select count(distinct ue.userid) 
            from {user_enrolments} ue
                inner join {role_assignments} ra on ra.userid = ue.userid
                inner join {context}  ctx on ctx.id = ra.contextid
                where  ra.roleid = 5 and ctx.id = ra.contextid
                    and  ctx.instanceid = ? and ctx.contextlevel = 50) as qtd_matriculados,
        (select count(*) from {quiz_attempts} qa
		where qa.userid = ? and qa.quiz = q.id
	                 ) as qtd_meus_acessos        
       from {quiz} q
         inner join {course_modules}  cm on cm.instance = q.id
         inner join {course_sections} cs on cs.id = cm.section
         inner join {modules} m on m.id = cm.module
      where q.course = ? and cm.visible =1 and m.name = 'quiz'
      group by q.name
      order by q.id";
    
    $params = array($course_id, $user_id, $course_id, $course_id,  $user_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;    
    
}


function block_dashlearner_get_user ($user_id) {
    global $DB;

    $sql = "select u.firstname as firstname, u.lastname as lastname, u.email as email
                from {user} u
                where u.id = ? ";

    $params = array($user_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_course ($course_id) {
    global $DB;

    $sql = "select c.fullname as fullname, c.shortname as shortname
                from {course} c
                where c.id = ? ";

    $params = array($course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_feedback($course_id, $user_id) {
    global $DB;

    $sql = "select  concat('[:',cs.name,']: ',f.name) as name, count(distinct log.userid) as qtd_usuarios_acessos,
                    (select  count(distinct ue.userid)
			from {user_enrolments} ue
			inner join {role_assignments} ra on ra.userid = ue.userid
			inner join {context} ctx on ctx.id = ra.contextid
			where ra.roleid = 5
			and ctx.instanceid = ? and ctx.contextlevel = 50)  as qtd_matriculados,
                    (select count(*)
			from {logstore_standard_log} log
                        inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
			where log.userid = ? and log.courseid = ? 
			and objecttable = 'feedback' and action = 'viewed'
                        and log.contextinstanceid = cm.id) as qtd_meus_acessos
		from {logstore_standard_log} log
		inner join {role_assignments} ra on ra.userid = log.userid
		inner join {course_modules} cm on cm.id = log.contextinstanceid
                inner join {course_sections} cs on cs.id = cm.section
                inner join {modules} m on m.id = cm.module
		inner join {feedback} f on f.id = cm.instance
		where objecttable = 'feedback' and log.courseid = ?	and ra.roleid = 5 and m.name = 'feedback' and cm.visible = 1
		group by f.name
		order by f.id";

    $params = array($course_id, $user_id, $course_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_acesses_by_user_table($course_id, $user_id, $table_name) {
    global $DB;

    $sql = "select  concat('[',cs.name,']: ',t.name) as name, count(distinct log.userid) as qtd_usuarios_acessos,
                    (select  count(distinct ue.userid)
			from {user_enrolments} ue
			inner join {role_assignments} ra on ra.userid = ue.userid
			inner join {context} ctx on ctx.id = ra.contextid
			where ra.roleid = 5 and ctx.instanceid = ? and ctx.contextlevel = 50) as qtd_matriculados,
                    (select count(*)
			from {logstore_standard_log} log
                        inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
			where log.userid = ? and log.courseid = ? 
			and objecttable = '".$table_name."'
                        and log.contextinstanceid = cm.id) as qtd_meus_acessos
		from {logstore_standard_log} log
		inner join {role_assignments} ra on ra.userid = log.userid
		inner join {course_modules} cm on cm.id = log.contextinstanceid and cm.visible = 1
                inner join {course_sections} cs on cs.id = cm.section
		inner join {".$table_name."} t on t.id = cm.instance
		where objecttable = '".$table_name."' and log.courseid = ?	and ra.roleid = 5
		group by t.name
		order by t.id";

    $params = array($course_id, $user_id, $course_id, $course_id);

    $result = $DB->get_records_sql($sql, $params);
    return $result;
}



function block_dashlearner_get_feedback_by_user ($course_id, $user_id) {
    global $DB;

    $sql = "select  concat('[',cs.name,']: ',f.name) as name, count(distinct log.userid) as qtd_usuarios_acessos,
                    (select  count(distinct ue.userid)
			from {user_enrolments}  ue
			inner join {role_assignments}  ra on ra.userid = ue.userid
			inner join {context}  ctx on ctx.id = ra.contextid
			where ra.roleid = 5
                        and ctx.instanceid = ? and ctx.contextlevel = 50)   as qtd_matriculados,
                    (select count(*)
			 from {logstore_standard_log}  log
                         inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
			   where log.userid = ? and log.courseid = ?
                        and objecttable = 'feedback'
                        and log.contextinstanceid = cm.id)   as qtd_meus_acessos,
                    (select count(distinct log.userid)
				from {logstore_standard_log}  log
                                inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
               		    where log.userid = ? and log.courseid = ?
                        and objecttable = 'feedback_completed'
                        and log.contextinstanceid = cm.id)   as flg_submissao_prazo
		from {logstore_standard_log}  log
		inner join {role_assignments}  ra on ra.userid = log.userid
		inner join {course_modules}  cm on cm.id = log.contextinstanceid
                inner join {course_sections} cs on cs.id = cm.section
		inner join {feedback}  f on f.id = cm.instance
		where objecttable = 'feedback_completed' and log.courseid = ?	and ra.roleid = 5 and cm.visible = 1
		group by f.name
		order by f.id";

    $params = array($course_id, $user_id, $course_id, $user_id, $course_id, $course_id);

    $result = $DB->get_records_sql($sql, $params);
    return $result;
}






function block_dashlearner_get_vpl_by_user ($course_id, $user_id) {
    global $DB;

    $sql = "select v.id as id,concat('[',cs.name,'] : ',v.name) as name,
          (select count(distinct x.userid)
              from {user_enrolments} ue
                inner join {role_assignments} ra on ra.userid = ue.userid
                inner join {context} ctx on ctx.id = ra.contextid
                inner join  {vpl_submissions}  x on   x.userid = ue.userid  
                where x.vpl = v.id and ra.roleid = 5 and ctx.id = ra.contextid 
                 and  ctx.instanceid = ? and ue.status = 0 and x.userid = ? and ctx.contextlevel = 50 )  as flg_submissao_prazo,
           (select count(distinct ue.userid) 
              from {user_enrolments}  ue
                inner join {role_assignments} ra on ra.userid = ue.userid
                inner join {context} ctx on ctx.id = ra.contextid
                inner join {vpl_submissions}  x on x.userid = ue.userid   
                where x.vpl = v.id and ra.roleid = 5 
                    and ctx.id = ra.contextid 
                    and  ctx.instanceid = ? and ue.status = 0 and ctx.contextlevel = 50)  as qtd_usuarios_acessos,
          (select count(distinct ue.userid) 
            from {user_enrolments} ue
                inner join {role_assignments} ra on ra.userid = ue.userid
                inner join {context} ctx on ctx.id = ra.contextid
                where  ra.roleid = 5 and ctx.id = ra.contextid
                    and  ctx.instanceid = ? and ctx.contextlevel = 50) as qtd_matriculados,
          (select count(*) 
               from {logstore_standard_log} log
                  where log.objecttable = 'vpl_submissions' 
		        and log.courseid = ? 
		        and log.contextinstanceid = cm.id
		        and log.userid = ? and log.action = 'evaluated') as qtd_meus_acessos         
      from {vpl} v
         inner join {course_modules} cm on cm.instance = v.id
         inner join {modules} m on m.id = cm.module
         inner join {course_sections} cs on cs.id = cm.section
      where v.course = ? and cm.visible = 1 and m.name = 'vpl'
      group by v.name
      order by v.id";
    
    $params = array($course_id, $user_id, $course_id, $course_id, $course_id,  $user_id, $course_id);

    $result = $DB->get_records_sql($sql, $params);
    return $result;
}     

function block_dashlearner_get_grades ($course_id, $user_id) {
    global $DB;

    $sql =  "SELECT  distinct 
            gi.itemname as itemname,
            gi.itemmodule as itemmodule,
            (select 
                 ifnull(truncate((gg.finalgrade/gi.grademax)*100,2),1000)
                 FROM {grade_grades}   gg    
                 where gg.itemid = gi.id and gg.userid = ?
              ) as perc_grade_user,
              (select 
                truncate(avg((gg.finalgrade/gi.grademax)*100),2)
                 FROM {grade_grades}    gg    
                 where gg.itemid = gi.id and gg.finalgrade is not null
              ) as perc_grade_course
          from  {grade_items}   gi
           inner join {course_modules}   cm on cm.course = gi.courseid and cm.visible = 1
          where cm.visible = 1 and gi.courseid = ?
             and  gi.itemmodule is not null
             and  exists (select 1 from {grade_grades}   x where x.itemid = gi.id and x.finalgrade is not null and x.finalgrade > 0)
          order by gi.itemmodule desc, gi.id ";
            
    $params = array($user_id, $course_id);

    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_assign_radar ($course_id, $user_id) {

    global $DB;
     $sql = "select count(*) as qty_assign_course_radar,
               (select count(*) 
                    from {assign_submission} mas  
               	inner join {assign} asn on asn.id = mas.assignment 
         	inner join {course_modules} cm on cm.instance = asn.id 
         	inner join {modules} m on cm.module = m.id
        	where asn.course = ? and cm.visible =1 and m.name = 'assign'
                	and mas.userid = ?) as qty_assign_user_radar
              from {assign} asn
              inner join {course_modules} cm on cm.instance = asn.id 
              inner join {modules} m on cm.module = m.id
              where asn.course = ? and cm.visible =1 and m.name = 'assign'";

    $params = array($course_id, $user_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_quiz_radar ($course_id, $user_id) {

    global $DB;
    $sql = "select count(*) as qty_quiz_course_radar,
               (select count(distinct mqa.quiz)
                    from  {quiz_attempts} mqa
                inner join {quiz} q on q.id = mqa.quiz
         	inner join {course_modules} cm on cm.instance = q.id 
         	inner join {modules} m on cm.module = m.id
      	        where q.course = ? and cm.visible =1 and m.name = 'quiz'and mqa.userid = ?
        	         ) as qty_quiz_user_radar
            from {quiz} q
            inner join {course_modules} cm on cm.instance = q.id 
            inner join {modules} m on cm.module = m.id
            where q.course = ? and cm.visible =1 and m.name = 'quiz'";

    $params = array($course_id, $user_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_vpl_radar ($course_id, $user_id) {
    global $DB;
    $sql = "select count(*) as qty_vpl_course_radar,
              (select count(distinct vs.vpl )
                    from  {vpl_submissions}  vs 
               inner join {vpl} v on v.id = vs.vpl 
               inner join {course_modules} cm on cm.instance = v.id 
               inner join {modules} m on cm.module = m.id
               where  v.course = 4 and vs.userid = ? )  as qty_vpl_user_radar
            from {vpl} v
            inner join {course_modules} cm on cm.instance = v.id
            inner join {modules} m on m.id = cm.module
            where v.course = 4 and cm.visible = 1 and m.name = 'vpl'";
    
    $params = array($course_id, $user_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_assign_total ($course_id, $user_id) {
    global $DB;
    $sql = "select
            count(*) as total_assign_course,
            (select count(*)
		from {assign_submission} mas
		inner join {assign} asn on asn.id = mas.assignment
		inner join {course_modules} cm on cm.instance = asn.id
		inner join {modules} m on 	cm.module = m.id
            where asn.course = ? and cm.visible = 1 and mas.status = 'submitted' and mas.timecreated <= asn.duedate
            	and m.name = 'assign' and mas.userid = ?) as total_assign_user_prazo,
            (select count(*)
		from {assign_submission} mas
		inner join {assign} asn on asn.id = mas.assignment
		inner join {course_modules} cm on cm.instance = asn.id
		inner join {modules} m on 	cm.module = m.id
                 where asn.course = ? and cm.visible = 1 and mas.status = 'submitted' and mas.timecreated > asn.duedate
                    and m.name = 'assign' and mas.userid =?) as total_assign_user_fora_prazo
            from {assign} asn
            inner join {course_modules} cm on	cm.instance = asn.id
            inner join {modules} m on	cm.module = m.id
            where	asn.course = ?	and cm.visible = 1	and m.name = 'assign'";
    
    $params = array($course_id, $user_id, $course_id, $user_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}
    

function block_dashlearner_get_feedback_total ($course_id, $user_id) {
    global $DB;
  
    $sql = "select  
             count(distinct f.id) as  total_feedback_course,
		(select count(distinct mlsl.contextid)
                    from {logstore_standard_log} mlsl 
                    inner join {course_modules} cmx on cmx.id = mlsl.contextinstanceid and cmx.visible = 1
                    where userid = ? and courseid  = ?
                    and objecttable  = 'feedback_completed') as total_feedback_user
            from {logstore_standard_log}  log
            inner join {role_assignments}  ra on ra.userid = log.userid
            inner join {course_modules}  cm on cm.id = log.contextinstanceid and cm.visible = 1
            inner join {feedback}  f on f.id = cm.instance
            where objecttable = 'feedback' and log.courseid = ?	and ra.roleid = 5";

    $params = array($user_id, $course_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
 
    return $result;
}
    

function block_dashlearner_get_total_by_user_table($course_id, $user_id, $table_name) {
    global $DB;

    $sql = "select  count(distinct t.id) total_table_course,
         (select count(distinct log.contextinstanceid )
			from {logstore_standard_log}  log
                        inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
			where log.userid = ? and log.courseid = ?
			and objecttable = '".$table_name."' ) as total_table_user
  		from {logstore_standard_log}  log
		inner join {role_assignments}  ra on ra.userid = log.userid
		inner join {course_modules}  cm on cm.id = log.contextinstanceid and cm.visible = 1
		inner join {".$table_name."} t on t.id = cm.instance
		where objecttable = '".$table_name."' and log.courseid = ?	and ra.roleid = 5";
            
    $params = array($user_id, $course_id, $course_id);

    $result = $DB->get_records_sql($sql, $params);
    return $result;
}


function block_dashlearner_get_vpl_total ($course_id, $user_id) {
    global $DB;
  
    $sql = "select count(distinct v.id) as total_vpl_course,
          (select count(distinct log.contextinstanceid ) 
               from {logstore_standard_log} log
               inner join {course_modules} cm on cm.id = log.contextinstanceid
               inner join {modules} m on m.id = cm.module
                  where log.objecttable = 'vpl_submissions' 
		    and log.courseid = ? 
		    and log.userid = ? and log.action = 'evaluated'
                    and cm.visible = 1 and m.name = 'vpl'
                    and cm.course = ? ) as total_vpl_user         
      from {vpl} v
         inner join {course_modules} cm on cm.instance = v.id
         inner join {modules} m on m.id = cm.module
      where v.course = ? and cm.visible = 1 and m.name = 'vpl'";
    
    $params = array($course_id, $user_id, $course_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}


function block_dashlearner_get_quiz_total ($course_id, $user_id) {
    global $DB;
  
    $sql = "select count(distinct q.id) as total_quiz_course,
          (select count(distinct mqa.quiz)
            from  {quiz_attempts} mqa
            inner join {quiz} q on q.id = mqa.quiz
         	inner join {course_modules} cm on cm.instance = q.id 
         	inner join {modules} m on cm.module = m.id
      	where q.course = ? and cm.visible =1 and m.name = 'quiz'and mqa.userid = ?
        	) as total_quiz_user
     from {quiz} q
         inner join {course_modules} cm on cm.instance = q.id 
         inner join {modules} m on cm.module = m.id
      where q.course = ? and cm.visible =1 and m.name = 'quiz'";
      
    $params = array($course_id, $user_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}
   
function block_dashlearner_get_avg_grade_course ($course_id) {
    global $DB;

    
    $sql = "select distinct gi.itemmodule as itemmodule, 
                  ifnull(truncate(sum(gg.finalgrade/gi.grademax)/count(gi.id),2)*10,0) as  avg_grade_course
                 from {grade_items} gi      
                 left join {grade_grades} gg  on gg.itemid = gi.id  
                where  gi.courseid = ?  
                and gi.itemmodule in ('assign','lesson','quiz','vpl') 
                and  exists (select 1 from {grade_grades}   x where x.itemid = gi.id and x.finalgrade is not null and x.finalgrade > 0)
                 group by gi.itemmodule 
                order by gi.itemmodule";
   
    $params = array($course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}

function block_dashlearner_get_avg_grade_user ($course_id, $user_id) {
    global $DB;

    $sql = "select distinct gi.itemmodule as itemmodule, 
                  ifnull(truncate(sum(gg.finalgrade/gi.grademax)/count(gi.id),2)*10,0) as  avg_grade_user
                 from {grade_items} gi      
                left join {grade_grades} gg  on gg.itemid = gi.id and gg.userid = ? 
                where  gi.courseid = ?  
                and gi.itemmodule in ('assign','lesson','quiz','vpl') 
                and  exists (select 1 from {grade_grades}   x where x.itemid = gi.id and x.finalgrade is not null and x.finalgrade > 0)
                group by gi.itemmodule 
                order by gi.itemmodule";
                
    $params = array($user_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}
    
function block_dashlearner_get_lesson($course_id, $user_id) {
    global $DB;
    
    $sql = "select  concat('[',l.name,'] ',lp.title ) as name,
               count(distinct log.userid) as qtd_usuarios_acessos,
			(select  count(distinct ue.userid)
				from {user_enrolments}  ue
				inner join {role_assignments}  ra on ra.userid = ue.userid
				inner join {context}  ctx on ctx.id = ra.contextid
				where ra.roleid = 5 and ctx.instanceid = ?  and ctx.contextlevel = 50) as qtd_matriculados,
			(select count(*)
				from {logstore_standard_log} log
                                inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
				where log.userid = ? and log.courseid = ? 
				and objecttable = 'lesson_pages' and log.action= 'viewed'
                                and log.objectid = lp.id) as qtd_meus_acessos			
            from {logstore_standard_log}  log
                    inner join {lesson_pages}  lp on lp.id = log.objectid 
                    inner join {lesson} l on l.id = lp.lessonid 
                    inner join {course_modules}  cm on cm.instance = l.id and cm.course = ? and cm.visible = 1
                    inner join {course_sections}  cs on cs.id = cm.section
                    inner join {role_assignments} ra on ra.userid = log.userid
            where log.objecttable = 'lesson_pages' and log.courseid = ? and ra.roleid = 5 and log.action='viewed'
                and l.course = ?  and cs.visible = 1
            group by concat('[',l.name,'] ',lp.title )
            order by l.id";
    
    $params = array($course_id, $user_id, $course_id, $course_id, $course_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}


function block_dashlearner_get_lesson_total ($course_id, $user_id) {
    global $DB;
  
    $sql = "
       select count(distinct lp.id)  as total_lesson_course,
             (select count(distinct log.objectid ) 
                   from {logstore_standard_log} log
                   inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
                   where log.objecttable = 'lesson_pages' 
		   and log.courseid = ?
		   and log.userid = ? and log.action = 'viewed') as total_lesson_user  
       from {lesson_pages}  lp
       inner join {lesson} l on l.id = lp.lessonid 
       inner join {course_modules} cm on cm.instance = l.id 
       inner join {modules} m on m.id = cm.module
       where cm.course = ? and m.name = 'lesson' and cm.visible = 1";
       
    $params = array($course_id, $user_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}



function block_dashlearner_get_wiki($course_id, $user_id) {
    global $DB;

    $sql = "select  concat('[',cs.name,']: ',t.name) as name, count(distinct log.userid) as qtd_usuarios_acessos,
                    (select  count(distinct ue.userid)
			from {user_enrolments} ue
			inner join {role_assignments} ra on ra.userid = ue.userid
			inner join {context} ctx on ctx.id = ra.contextid
			where ra.roleid = 5 and ctx.instanceid = ? and ctx.contextlevel = 50) as qtd_matriculados,
                    (select count(*)
			from {logstore_standard_log} log
                        inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
			where log.userid = ? and log.courseid = ?  and crud in  ('c')
			and objecttable = 'wiki_pages'
                        and log.contextinstanceid = cm.id) as qtd_comentarios_criados,
                    (select count(*)
			from {logstore_standard_log} log
                        inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
			where log.userid = ? and log.courseid = ?  and crud in  ('r')
			and objecttable = 'wiki_pages'
                        and log.contextinstanceid = cm.id) as qtd_comentarios_acessados,
                    (select count(*)
			from {logstore_standard_log} log
                        inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
			where log.userid = ? and log.courseid = ?  and crud in  ('u')
			and objecttable = 'wiki_pages'
                        and log.contextinstanceid = cm.id) as qtd_comentarios_atualizados
		from {logstore_standard_log} log
		inner join {role_assignments} ra on ra.userid = log.userid
		inner join {course_modules} cm on cm.id = log.contextinstanceid
                inner join {course_sections} cs on cs.id = cm.section
		inner join {wiki} t on t.id = cm.instance
		where objecttable = 'wiki_pages' and log.courseid = ?	and ra.roleid = 5 and crud in  ('r','c','u') and cm.visible = 1
		group by t.name
		order by t.id";

    $params = array($course_id, $user_id, $course_id, $user_id, $course_id, $user_id, $course_id, $course_id);

    $result = $DB->get_records_sql($sql, $params);
    return $result;
}


function block_dashlearner_get_wiki_total ($course_id, $user_id) {
    global $DB;
  
    $sql = "
         select count(distinct w.id)  as total_wiki_course,
             (select count(distinct ws.wikiid) 
                   from {logstore_standard_log} log
                   inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
                   inner join {wiki_pages} wp on wp.id = log.objectid 
                   inner join {wiki_subwikis} ws  on ws.id = wp.subwikiid 
                  where log.objecttable = 'wiki_pages' 
		   and log.courseid = ?
		   and log.userid = ? and log.crud in ('c','r','u')) as total_wiki_user  
       from {wiki}  w
       inner join {course_modules} cm on cm.instance = w.id 
       inner join {modules} m on m.id = cm.module
       where cm.course = ? and m.name = 'wiki' and cm.visible = 1";
    
    $params = array($course_id, $user_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}


function block_dashlearner_get_forum_by_user ($course_id, $user_id) {
    global $DB;

    $sql = "    
        select f.id as id,concat('[',cs.name,'] : ',f.name) as name,
            (select count(distinct log.userid) 
               from {logstore_standard_log}  log
               inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
               inner join {role_assignments} ra on ra.userid = log.userid
               inner join {context} ctx on ctx.id=ra.contextid 
                  where log.objecttable = 'forum' 
		        and log.courseid = ?
		        and log.contextinstanceid = cm.id
		        and log.action = 'viewed'
		        and ctx.instanceid = ? and ctx.contextlevel = 50) as qtd_usuarios_acessos,
		  (select count(distinct ue.userid) 
            from {user_enrolments}  ue
                inner join {role_assignments}  ra on ra.userid = ue.userid
                inner join {context}  ctx on ctx.id = ra.contextid
                where  ra.roleid = 5 and ctx.id = ra.contextid
                    and  ctx.instanceid = ? and ctx.contextlevel = 50) as qtd_matriculados,
          (select count(*) 
               from {logstore_standard_log}  log
               inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
                  where log.objecttable = 'forum' 
		        and log.courseid = ?
		        and log.contextinstanceid = cm.id
		        and log.userid = ? and log.action = 'viewed') as qtd_forum_acessados,
		   (select count(distinct objectid)
				from {logstore_standard_log}  log
                                inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
				where log.userid = ? and log.courseid = ?
				and objecttable like  'forum_discussions' and action = 'created'
                        and log.contextinstanceid = cm.id)  as qtd_topicos_criados,
            (select count(distinct objectid)
				from {logstore_standard_log}  log
                                inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
				where log.userid = ? and log.courseid = ?
				and objecttable like  'forum_posts' and action = 'uploaded'
                        and log.contextinstanceid = cm.id) as qtd_topicos_comentados
      from {forum}  f
         inner join {course_modules}  cm on cm.instance = f.id
         inner join {modules}  m on m.id = cm.module
         inner join {course_sections}  cs on cs.id = cm.section
      where f.course = ? and cm.visible = 1 and m.name = 'forum'
      group by f.name
      order by f.id";    
    
    $params = array($course_id, $course_id, $course_id, $course_id, $user_id, $user_id, $course_id , $user_id, $course_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}
    

function block_dashlearner_get_forum_total ($course_id, $user_id) {
    global $DB;
                        
    $sql = "select count(distinct f.id)  as total_forum_course,
             (select count(distinct log.objectid ) 
                   from {logstore_standard_log} log
                   inner join {course_modules} cmx on cmx.id = log.contextinstanceid and cmx.visible = 1 
                   where log.objecttable = 'forum' 
		   and log.courseid = ?
		   and log.userid = ? and log.action = 'viewed') as total_forum_user  
       from {forum} f
       inner join {course_modules} cm on cm.instance = f.id 
       inner join {modules} m on m.id = cm.module
       where cm.course = ? and m.name = 'forum' and cm.visible = 1";
    
    $params = array($course_id, $user_id, $course_id);
    $result = $DB->get_records_sql($sql, $params);
    return $result;
}
    
                        
    