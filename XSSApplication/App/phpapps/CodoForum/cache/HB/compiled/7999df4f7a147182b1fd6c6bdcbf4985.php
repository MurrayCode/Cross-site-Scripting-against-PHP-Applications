<?php return function ($in, $debugopt = 1) {
    $cx = array(
        'flags' => array(
            'jstrue' => false,
            'jsobj' => false,
            'spvar' => true,
            'prop' => false,
            'method' => false,
            'mustlok' => false,
            'mustsec' => false,
            'debug' => $debugopt,
        ),
        'helpers' => array(            'const' => function($args) {
                                //single argument call
                                return constant($args[0]);
                            },
            'i18n' => function($args) {

                                return _t($args[0]);
                            },
),
        'blockhelpers' => array(),
        'hbhelpers' => array(),
        'partials' => array(),
        'scopes' => array($in),
        'sp_vars' => array(),
'funcs' => array(
    'sec' => function ($cx, $v, $in, $each, $cb, $inv = null) {
        $isAry = is_array($v);
        $isTrav = $v instanceof Traversable;
        $loop = $each;
        $keys = null;
        $last = null;
        $isObj = false;

        if ($isAry && $inv !== null && count($v) === 0) {
            return $inv($cx, $in);
        }

        // #var, detect input type is object or not
        if (!$loop && $isAry) {
            $keys = array_keys($v);
            $loop = (count(array_diff_key($v, array_keys($keys))) == 0);
            $isObj = !$loop;
        }

        if (($loop && $isAry) || $isTrav) {
            if ($each && !$isTrav) {
                // Detect input type is object or not when never done once
                if ($keys == null) {
                    $keys = array_keys($v);
                    $isObj = (count(array_diff_key($v, array_keys($keys))) > 0);
                }
            }
            $ret = array();
            $cx['scopes'][] = $in;
            $i = 0;
            if ($cx['flags']['spvar'] && !$isTrav) {
                $last = count($keys) - 1;
            }
            foreach ($v as $index => $raw) {
                if ($cx['flags']['spvar']) {
                    $cx['sp_vars']['first'] = ($i === 0);
                    if ($isObj || $isTrav) {
                        $cx['sp_vars']['key'] = $index;
                        $cx['sp_vars']['index'] = $i;
                    } else {
                        $cx['sp_vars']['last'] = ($i == $last);
                        $cx['sp_vars']['index'] = $index;
                    }
                    $i++;
                }
                $ret[] = $cb($cx, $raw);
            }
            if ($cx['flags']['spvar']) {
                if ($isObj) {
                    unset($cx['sp_vars']['key']);
                } else {
                    unset($cx['sp_vars']['last']);
                }
                unset($cx['sp_vars']['index']);
                unset($cx['sp_vars']['first']);
            }
            array_pop($cx['scopes']);
            return join('', $ret);
        }
        if ($each) {
            if ($inv !== null) {
                $cx['scopes'][] = $in;
                $ret = $inv($cx, $v);
                array_pop($cx['scopes']);
                return $ret;
            }
            return '';
        }
        if ($isAry) {
            if ($cx['flags']['mustsec']) {
                $cx['scopes'][] = $v;
            }
            $ret = $cb($cx, $v);
            if ($cx['flags']['mustsec']) {
                array_pop($cx['scopes']);
            }
            return $ret;
        }

        if ($v === true) {
            return $cb($cx, $in);
        }

        if (!is_null($v) && ($v !== false)) {
            return $cb($cx, $v);
        }

        if ($inv !== null) {
            return $inv($cx, $in);
        }

        return '';
    },
    'ifvar' => function ($cx, $v) {
        return !is_null($v) && ($v !== false) && ($v !== 0) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
    },
    'ch' => function ($cx, $ch, $vars, $op) {
        return $cx['funcs']['chret'](call_user_func_array($cx['helpers'][$ch], $vars), $op);
    },
    'chret' => function ($ret, $op) {
        if (is_array($ret)) {
            if (isset($ret[1]) && $ret[1]) {
                $op = $ret[1];
            }
            $ret = $ret[0];
        }

        switch ($op) {
            case 'enc':
                return htmlentities($ret, ENT_QUOTES, 'UTF-8');
            case 'encq':
                return preg_replace('/&#039;/', '&#x27;', htmlentities($ret, ENT_QUOTES, 'UTF-8'));
        }
        return $ret;
    },
)

    );
    return ''.$cx['funcs']['sec']($cx, ((isset($in['posts']) && is_array($in)) ? $in['posts'] : null), $in, true, function($cx, $in) {return '<a name="post-'.htmlentities((string)((isset($in['post_id']) && is_array($in)) ? $in['post_id'] : null), ENT_QUOTES, 'UTF-8').'"></a>
<article id="post-'.htmlentities((string)((isset($in['post_id']) && is_array($in)) ? $in['post_id'] : null), ENT_QUOTES, 'UTF-8').'" class="clearfix">

    <div class="codo_posts_post_moderation">


'.(($cx['funcs']['ifvar']($cx, ((isset($in['is_topic']) && is_array($in)) ? $in['is_topic'] : null))) ? ''.(($cx['funcs']['ifvar']($cx, ((isset($in['can_manage_topic']) && is_array($in)) ? $in['can_manage_topic'] : null))) ? '        <div class="dropdown codo_manage_topic">
            <div class="codo_manage_button dropdown-toggle" data-toggle="dropdown"  id="codo_manage_options_menu">
                <i class="icon-more-vert"></i>
            </div>
            <ul class="dropdown-menu" role="menu" aria-labelledby="codo_manage_options_menu">
'.(($cx['funcs']['ifvar']($cx, ((isset($in['can_edit_topic']) && is_array($in)) ? $in['can_edit_topic'] : null))) ? '                <li id="codo_posts_edit_'.((isset($in['topic_id']) && is_array($in)) ? $in['topic_id'] : null).'" class="codo_posts_edit_post codo_post_this_is_topic"
                    role="presentation"><a role="menuitem" tabindex="-1">
                        <i class="icon-edit"></i> '.$cx['funcs']['ch']($cx, 'i18n', array(array('Edit'),array()), 'raw').'
                    </a></li>
' : '').''.(($cx['funcs']['ifvar']($cx, ((isset($in['can_delete_topic']) && is_array($in)) ? $in['can_delete_topic'] : null))) ? '                <li id="codo_posts_trash_'.((isset($in['topic_id']) && is_array($in)) ? $in['topic_id'] : null).'" class="codo_posts_trash_post codo_post_this_is_topic"
                    role="presentation"><a role="menuitem" tabindex="-1">
                        <i class="icon-trash"></i> '.$cx['funcs']['ch']($cx, 'i18n', array(array('Delete'),array()), 'raw').'
                    </a></li>
' : '').'            </ul>
        </div>
' : '').'
' : '
'.(($cx['funcs']['ifvar']($cx, ((isset($in['can_manage_post']) && is_array($in)) ? $in['can_manage_post'] : null))) ? '        <div class="dropdown codo_manage_topic">
            <div class="codo_manage_button dropdown-toggle" data-toggle="dropdown"  id="codo_manage_options_menu">
                <i class="icon-more-vert"></i>
            </div>
            <ul class="dropdown-menu" role="menu" aria-labelledby="codo_manage_options_menu">
'.(($cx['funcs']['ifvar']($cx, ((isset($in['can_edit_post']) && is_array($in)) ? $in['can_edit_post'] : null))) ? '                <li id="codo_posts_edit_'.((isset($in['post_id']) && is_array($in)) ? $in['post_id'] : null).'" class="codo_posts_edit_post codo_post_this_is_post"
                    role="presentation"><a role="menuitem" tabindex="-1">
                        <i class="icon-edit"></i> '.$cx['funcs']['ch']($cx, 'i18n', array(array('Edit'),array()), 'raw').'
                    </a></li>
' : '').''.(($cx['funcs']['ifvar']($cx, ((isset($in['can_delete_post']) && is_array($in)) ? $in['can_delete_post'] : null))) ? '                <li id="codo_posts_trash_'.((isset($in['post_id']) && is_array($in)) ? $in['post_id'] : null).'" class="codo_posts_trash_post codo_post_this_is_post"
                    role="presentation"><a role="menuitem" tabindex="-1">
                        <i class="icon-trash"></i> '.$cx['funcs']['ch']($cx, 'i18n', array(array('Delete'),array()), 'raw').'
                    </a></li>
' : '').''.(($cx['funcs']['ifvar']($cx, ((isset($in['can_see_history']) && is_array($in)) ? $in['can_see_history'] : null))) ? '                <li id="codo_posts_history_'.((isset($in['post_id']) && is_array($in)) ? $in['post_id'] : null).'" class="codo_posts_history"
                    role="presentation"><a role="menuitem" tabindex="-1">
                        <i class="glyphicon glyphicon-time"></i> '.$cx['funcs']['ch']($cx, 'i18n', array(array('History'),array()), 'raw').'
                    </a></li>
' : '').'
            </ul>
        </div>
' : '').'').'    </div>

    <div class="codo_posts_user_info">
        <div class="codo_posts_post_avatar">
            <a href="'.$cx['funcs']['ch']($cx, 'const', array(array('RURI'),array()), 'raw').'user/profile/'.htmlentities((string)((isset($in['id']) && is_array($in)) ? $in['id'] : null), ENT_QUOTES, 'UTF-8').'">
                <img draggable="false" src="'.htmlentities((string)((isset($in['avatar']) && is_array($in)) ? $in['avatar'] : null), ENT_QUOTES, 'UTF-8').'" />
            </a>
        </div>
        <!--<div class="codo_posts_post_title">

        </div>-->

        <div class="codo_posts_post_name">
            <a href="'.$cx['funcs']['ch']($cx, 'const', array(array('RURI'),array()), 'raw').'user/profile/'.htmlentities((string)((isset($in['id']) && is_array($in)) ? $in['id'] : null), ENT_QUOTES, 'UTF-8').'">'.htmlentities((string)((isset($in['name']) && is_array($in)) ? $in['name'] : null), ENT_QUOTES, 'UTF-8').'</a>
        </div>

        <div class="codo_posts_post_desc">
'.(($cx['funcs']['ifvar']($cx, ((isset($in['in_search']) && is_array($in)) ? $in['in_search'] : null))) ? '            '.$cx['funcs']['ch']($cx, 'i18n', array(array('posted'),array()), 'raw').'&nbsp;<a href="'.$cx['funcs']['ch']($cx, 'const', array(array('RURI'),array()), 'raw').'topic/'.htmlentities((string)((isset($in['tid']) && is_array($in)) ? $in['tid'] : null), ENT_QUOTES, 'UTF-8').'/'.htmlentities((string)((isset($in['safe_title']) && is_array($in)) ? $in['safe_title'] : null), ENT_QUOTES, 'UTF-8').'/post-'.htmlentities((string)((isset($in['post_id']) && is_array($in)) ? $in['post_id'] : null), ENT_QUOTES, 'UTF-8').'#post-'.htmlentities((string)((isset($in['post_id']) && is_array($in)) ? $in['post_id'] : null), ENT_QUOTES, 'UTF-8').'">'.htmlentities((string)((isset($in['post_created']) && is_array($in)) ? $in['post_created'] : null), ENT_QUOTES, 'UTF-8').'</a>
' : '            <span>
                '.$cx['funcs']['ch']($cx, 'i18n', array(array('posted'),array()), 'raw').'&nbsp;<a href="'.$cx['funcs']['ch']($cx, 'const', array(array('RURI'),array()), 'raw').'topic/'.htmlentities((string)((isset($in['tid']) && is_array($in)) ? $in['tid'] : null), ENT_QUOTES, 'UTF-8').'/'.htmlentities((string)((isset($in['safe_title']) && is_array($in)) ? $in['safe_title'] : null), ENT_QUOTES, 'UTF-8').'/'.htmlentities((string)((isset($in['page']) && is_array($in)) ? $in['page'] : null), ENT_QUOTES, 'UTF-8').'#post-'.htmlentities((string)((isset($in['post_id']) && is_array($in)) ? $in['post_id'] : null), ENT_QUOTES, 'UTF-8').'">'.htmlentities((string)((isset($in['post_created']) && is_array($in)) ? $in['post_created'] : null), ENT_QUOTES, 'UTF-8').'</a>
            </span>
').'        </div>

        <div class="codo_posts_user_spec">
        </div>
    </div>
    <div class="codo_posts_post_content">
        <div class="codo_posts_post_message">'.((isset($in['message']) && is_array($in)) ? $in['message'] : null).'</div>
        <div class="codo_posts_post_imessage">'.htmlentities((string)((isset($in['imessage']) && is_array($in)) ? $in['imessage'] : null), ENT_QUOTES, 'UTF-8').'</div>

'.(($cx['funcs']['ifvar']($cx, ((isset($in['signature']) && is_array($in)) ? $in['signature'] : null))) ? '        <div class="codo_posts_signature">'.((isset($in['signature']) && is_array($in)) ? $in['signature'] : null).'</div>
' : '').'
'.(($cx['funcs']['ifvar']($cx, ((isset($in['post_modified']) && is_array($in)) ? $in['post_modified'] : null))) ? '        <div class="codo_last_edit">'.$cx['funcs']['ch']($cx, 'i18n', array(array('edited'),array()), 'raw').' '.htmlentities((string)((isset($in['post_modified']) && is_array($in)) ? $in['post_modified'] : null), ENT_QUOTES, 'UTF-8').'</div>
' : '').'    </div>

    <div class="codo_posts_post_foot clearfix">
'.(($cx['funcs']['ifvar']($cx, ((isset($in['can_reply']) && is_array($in)) ? $in['can_reply'] : null))) ? '
        <div class="codo_posts_post_reputation" id="codo_posts_rep_'.((isset($in['post_id']) && is_array($in)) ? $in['post_id'] : null).'">
            <div class="codo_post_min_height">&nbsp;</div>
            <div class="codo_reputation_points codo_tooltip" data-placement="right" title="'.$cx['funcs']['ch']($cx, 'i18n', array(array('reputation points'),array()), 'encq').'">'.htmlentities((string)((isset($in['reputation']) && is_array($in)) ? $in['reputation'] : null), ENT_QUOTES, 'UTF-8').'</div>
            <div class="codo_btn codo_btn_danger codo_rep_up_btn codo_tooltip" title="'.$cx['funcs']['ch']($cx, 'i18n', array(array('vote up'),array()), 'encq').'"><i class="glyphicon glyphicon-plus"></i></div>
            <div class="codo_btn codo_btn_danger codo_rep_down_btn codo_tooltip" title="'.$cx['funcs']['ch']($cx, 'i18n', array(array('vote down'),array()), 'encq').'" data-placement="bottom"><i class="glyphicon glyphicon-minus"></i></div>
        </div>
        <div class="codo_posts_post_action">

            <div class="btn-group">
                <div class="codo_btn_def codo_quote_btn"><i class="icon-quote"></i></div>
                <div class="codo_btn_primary codo_btn codo_reply_btn">'.$cx['funcs']['ch']($cx, 'i18n', array(array('reply'),array()), 'raw').'</div>
            </div>
        </div>
' : '').'    </div>

</article>
<div class="codo_topic_separator"></div>
';}).'';
}
?>