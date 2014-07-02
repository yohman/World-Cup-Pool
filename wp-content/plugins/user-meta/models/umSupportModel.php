<?php

if( !class_exists( 'umSupportModel' ) ) :
class umSupportModel {  
    
    function methodPack( $methodName ){
        global $userMeta;
        $html = null;
        $html .= $userMeta->nonceField();
        $html .= $userMeta->methodName( $methodName );
        $html .= $userMeta->wp_nonce_field( $methodName, 'um_post_method_nonce', false, false );   
        if( !empty( $_SERVER['HTTP_REFERER'] ) ){
            $ref    = !empty( $_REQUEST['pf_http_referer'] ) ? esc_attr( $_REQUEST['pf_http_referer'] ) : esc_attr( $_SERVER['HTTP_REFERER'] );
            $html  .= "<input type=\"hidden\" name=\"pf_http_referer\" value=\"" . $ref . "\">";        
        }
        return $html;
    }
    
    
    /**
     * get name of forms
     */
    function getFormsName(){
        global $userMeta;
        
        $formsList = array();
        $forms = $userMeta->getData( 'forms' );  
        if( is_array( $forms ) ){
            foreach( $forms as $key => $val )
                $formsList[] = $key;
        }  
        return $formsList;
    }
    
    
    /**
     * Get Form Data with fields data within $form['fields']
     * 
     * @param type $formName
     * @return false if form not found || full form array including fields.
     */
    function getFormData( $formName ){
        global $userMeta;
               
        $forms  = $userMeta->getData('forms'); 
        if( empty( $forms[$formName] ) )
            return new WP_Error( 'no_form', sprintf( __( 'Form "%s" is not found.', $userMeta->name ), $formName ) );
        
        $form = $forms[$formName];
        if( empty( $form['fields'] ) )       
            return $form;              
        
        if( is_array( $form['fields'] ) ){
            $fields = array();
            $allFields = $userMeta->getData( 'fields' );
            foreach( $form['fields'] as $key => $fieldID ){
                if( isset( $allFields[ $fieldID ] ) ){
                    $field  = array();
                    $field[ 'field_id' ]    = $fieldID;
                    if( is_array( $allFields[ $fieldID ] ) )
                        $field  = $field + $allFields[ $fieldID ];
                    
                    $fields[ $fieldID ] = $field;
                }                   
            }
            $form['fields'] = $fields;           
        }
        
        return $form;
    }
            
    
    /**
     * Get um fields by 
     * @param $by: key, field_group
     * @param $value: 
     */    
    function getFields( $by=null, $param=null, $get=null, $isFree=false ){
        global $userMeta;
        $fieldsList = $userMeta->umFields();
        
        if( !$by )
            return $fieldsList;
        
        //$result = array();
        if( $param ){
            if( $by == 'key' ){
                if( key_exists( $param, $fieldsList ) )
                    return $fieldsList[$param];
            }else{
                foreach( $fieldsList as $key => $fieldData ){
                    if( $fieldData[$by] == $param ){
                        if( $isFree ){
                            if( !$fieldData['is_free'] ) continue;
                        }
                                                
                        if( !$get )
                            $result[$key] = $fieldData;
                        else    
                            $result[$key] = $fieldData[$get];
                    }
                }
            }
        }      
        
        return isset( $result ) ? $result : false;
    }    
     
    
    /**
     * Extract fielddata from fieldID
     * @param $fieldID
     * @param $fieldData : if $fieldData not set the it will search for field option for fielddata
     * @return array: field Data
     */
    function getFieldData( $fieldID, $fieldData=null ){
        global $userMeta;       
        
        if( !$fieldData ){
            $fields = $userMeta->getData( 'fields' );
            if( !isset($fields[$fieldID]) ) return;
            $fieldData = $fields[$fieldID];
        }
        
        //Setting Field Group
        $field_type_data   = $userMeta->getFields( 'key', $fieldData['field_type'] );
        $field_group        = $field_type_data['field_group'];                
                
        //Setting Field Name
        $fieldName = null;
        if( $field_group == 'wp_default' ){
            $fieldName = $fieldData['field_type'];
        }else{
           if( isset($fields[$fieldID]['meta_key']) )
                $fieldName = $fieldData['meta_key'];
        }              
        
        // Check if field is readonly
        $readOnly = @$fieldData['read_only'];
        if( !@$readOnly && @$fieldData['read_only_non_admin'] )
            $readOnly = $userMeta->isAdmin() ? false : true;          
        
        $returnData = $fieldData;
        $returnData['field_id']     = $fieldID;
        $returnData['field_group']  = $field_group;
        $returnData['field_name']   = $fieldName;
        $returnData['meta_key']     = isset($fieldData['meta_key']) ? $fieldData['meta_key'] : null;
        $returnData['field_title']  = isset($fieldData['field_title']) ? $fieldData['field_title'] : null;
        $returnData['required']     = isset($fieldData['required']) ? true : false;
        $returnData['unique']       = isset($fieldData['unique']) ? true : false;
        $returnData['read_only']    = @$readOnly;
        
        return $returnData;
    }
     
    
    /**
     * Get Custom Fields from 'Fields Editor'.
     * @return array of meta_key if success, false if no meta key.
     */
    function getCustomFields(){
        global $userMeta;
        
        $fields = $userMeta->getData( 'fields' );
        if( !$fields || !is_array( $fields ) ) return false;
        
        foreach( $fields as $field ){
            if( @$field['meta_key'] )
                $metaKeys[] = $field['meta_key'];
        }        
        return @$metaKeys ? $metaKeys : false;
    }
    

    /**
     * Add Custom Fields to 'Fields Editor'.
     * @param array $metaKeys: meta_key array which will be added.
     * @return bool: true if updadated, false if fail.
     */
    function addCustomFields( $metaKeys=array() ){
        global $userMeta;
        if( !$metaKeys || !is_array( $metaKeys ) ) return false;
        
        $fields = $userMeta->getData( 'fields' );      
        $existingKeys = $this->getCustomFields();
            
        foreach( $metaKeys as $meta ){
            if( !$existingKeys )
                $add = true;
            elseif( !in_array( $meta, $existingKeys ) )
                $add = true;
            
            if( @$add ) {
                $fields[] = array(
                    'field_title'       => $meta,
                    'field_type'        => 'text',
                    'title_position'    => 'top',
                    'meta_key'          => $meta
                );                
            } 
            unset( $add );                
        } 
        return $userMeta->updateData( 'fields', $fields );                      
    }
    

    /**
     * Validate input field from a form
     * @param $form_key
     * @return array: key=field_name 
     */
    function formValidInputField( $form_key ){
        global $userMeta;
        
        $forms  = $userMeta->getData( 'forms' );      
        if( !isset( $forms[ $form_key ][ 'fields' ] ) ) return;
        
        if( ! is_array( $forms[ $form_key ][ 'fields' ] ) ) return;
        
        foreach( $forms[ $form_key ][ 'fields' ] as $fieldID ){
            $fieldData  = $this->getFieldData( $fieldID );
            if( $fieldData['field_group'] == 'wp_default' OR $fieldData['field_group'] == 'standard' ){
                if( $fieldData['field_group'] == 'standard' AND !isset($fieldData['meta_key']) ) continue;
                if( @$fieldData['read_only'] ) continue;
                   
                $validField[ $fieldData[ 'field_name' ] ] = $fieldData;
                
                //$validField[ $fieldData[ 'field_name' ] ][ 'field_title' ] = $fieldData[ 'field_title' ];
                //$validField[ $fieldData[ 'field_name' ] ][ 'field_type' ]  = $fieldData[ 'field_type' ];
                //$validField[ $fieldData[ 'field_name' ] ][ 'required' ]    = $fieldData[ 'required' ];
                //$validField[ $fieldData[ 'field_name' ] ][ 'unique' ]      = $fieldData[ 'unique' ];              
            }        
        }
        
        return isset($validField) ? $validField : null;
    }
    
    
    function removeFromFileCache( $filePath ) {
        global $userMeta;
        
        if ( empty( $filePath ) ) return;
        
        $cache  = $userMeta->getData( 'cache' );
        $fileCache = isset( $cache['file_cache'] ) ? $cache['file_cache'] : array();
        
        $key = array_search( $filePath, $fileCache );
        if ( $key ) {
            unset( $cache['file_cache'][ $key ] );
            $userMeta->updateData( 'cache', $cache );
        }
    }
    
    
    function cleanupFileCache() {
        global $userMeta;
        $cache  = $userMeta->getData( 'cache' );
        
        $fileCache = isset( $cache['file_cache'] ) ? $cache['file_cache'] : array();
        if( empty( $fileCache ) || ! is_array( $fileCache ) ) return;
        
        $time = time() - ( 60 * 60 * 10 ); //10h
        foreach ( $fileCache as $key => $filePath ) {
            if ( $key < $time ) {
                unset( $cache['file_cache'][ $key ] );
                if ( file_exists( WP_CONTENT_DIR . $filePath ) )
                    unlink( WP_CONTENT_DIR . $filePath );
            }
        }
        $userMeta->updateData( 'cache', $cache ); 
    }
    
    // Not in use since 1.1.5rc3
    function removeCache( $cacheType, $cacheValue, $byKey=true ){
        global $userMeta;
        
        $cache  = $userMeta->getData( 'cache' );
        if( isset($cache[$cacheType]) ){            
            if( !is_array( $cacheValue ) )
                $cacheValue = array($cacheValue);
                
            foreach( $cacheValue as $key => $val ){
                $cacheKey = $val;
                if( !$byKey )
                    $cacheKey = array_search( $val, $cache[$cacheType] );   
                unset( $cache[$cacheType][$cacheKey] );             
            }
            $userMeta->updateData( 'cache', $cache );
        }           
    }
    
    // Not in use since 1.1.5rc3
    function clearCache(){
        global $userMeta;
        $cache  = $userMeta->getData( 'cache' );
        
        unset( $cache[ 'version' ] );
        unset( $cache[ 'version_type' ] );
        unset( $cache[ 'upgrade' ] );
        unset( $cache[ 'image_cache' ] );
        
        $csv_files = $cache[ 'csv_files' ];
        
        if( is_array( $csv_files ) ){
            foreach( $csv_files as $key => $val ){
                $time = time() - ( 3600 * 6 );
                if( $key < $time )
                    unset( $cache[ 'csv_files' ][ $key ] );
            }            
        }
        
        $userMeta->updateData( 'cache', $cache );
    }
    
    
    // Sleep
    function isUpgradationNeeded(){
        global $userMeta;
        
        // check upgrade flug
        $cache = $userMeta->getData( 'cache' ); 
        if( isset( $cache['upgrade']['1.0.3']['fields_upgraded'] ) )
            return false;        
           
        // Check data exists in new version
        $fields = $userMeta->getData( 'fields' );
        $exists = false;
        if( $fields ){
            if( is_array($fields) ){
                foreach( $fields as $value ){
                    if( isset($value['field_type']) )
                        $exists = true;
                }
            }
        }
        if($exists) return false;   
        
        $prevDefaultFields  = get_option( 'user_meta_field_checked' ); 
        $prevFields         = get_option( 'user_meta_field' );
        if( $prevDefaultFields or $prevFields )
            return true;             
    }
    
        
    function ajaxUmCommonRequest(){
        global $userMeta;
        $userMeta->verifyNonce();        
        die();
    }    
    
    
    function getProfileLink( $pre=null ){
        global $userMeta;
        
        $general = $userMeta->getSettings( 'general' );
        if( @$general[ 'profile_page' ] )
            $link = get_permalink( $general[ 'profile_page' ] );
        else
            $link = admin_url( 'profile.php' ); 
        
        return $link;
    }
            
    
    function pluginUpdateUrl(){
        global $userMeta;
        $plugin = $userMeta->pluginSlug;
        $url = wp_nonce_url( "update.php?action=upgrade-plugin&plugin=$plugin", "upgrade-plugin_$plugin" );                
        return $url = admin_url( htmlspecialchars_decode( $url ) );                                        
    }
    
    
    function getSettings( $key ){
        global $userMeta;
        
        $settings   = $userMeta->getData( 'settings' );
        $data       = @$settings[ $key ];
        
        if( !$data )
            $data   = $userMeta->defaultSettingsArray( $key );
        
        return $data;
    }
    
    
    /**
     * Get Email Template. if database is empty then use default data.
     * @param   : string $key
     * @return  : array
     */
    function getEmailsData( $key ){
        global $userMeta;
        
        $data       = $userMeta->getData( 'emails' );
        $emails     = @$data[ $key ];
        
        //if( empty( $emails ) ){
            $default    = $userMeta->defaultEmailsArray( $key );  
            $roles      = $userMeta->getRoleList();   
            
            if(  empty( $emails[ 'user_email' ] ) )
                $emails[ 'user_email' ][ 'um_disable' ] = @$default[ 'user_email' ][ 'um_disable' ];    
             if(  empty( $emails[ 'admin_email' ] ) )
                $emails[ 'admin_email' ][ 'um_disable' ] = @$default[ 'admin_email' ][ 'um_disable' ];             
            
            foreach( $roles as $key => $val ){
                if( empty( $emails[ 'user_email' ][ $key ][ 'subject' ] ) )
                    $emails[ 'user_email' ][ $key ][ 'subject' ]    = @$default[ 'user_email' ][ 'subject' ];
                if( empty( $emails[ 'user_email' ][ $key ][ 'body' ] ) )
                    $emails[ 'user_email' ][ $key ][ 'body' ]       = @$default[ 'user_email' ][ 'body' ];                 
                      
                if( empty( $emails[ 'admin_email' ][ $key ][ 'subject' ] ) )
                    $emails[ 'admin_email' ][ $key ][ 'subject' ]    = @$default[ 'admin_email' ][ 'subject' ];
                if( empty( $emails[ 'admin_email' ][ $key ][ 'body' ] ) )
                    $emails[ 'admin_email' ][ $key ][ 'body' ]       = @$default[ 'admin_email' ][ 'body' ];                              
            }                
        //}
                              
        return $emails;     
    }
    
    
    function getAllAdminEmail() {
        $emails = array( get_bloginfo( 'admin_email' ) );
        
        $users = get_users( array( 'role' => 'administrator' ) );
        foreach( $users as $user ) {
            $emails[] = $user->user_email;
        }
        
        return array_unique( $emails );
    }
    
    
    /**
     * Filter role based on given role. In givn role, role key should be use as array value.
     * 
     * @param type (array) $roles
     */
    function allowedRoles( $allowedRoles ){
        global $userMeta;
        $roles = $userMeta->getRoleList(true);
        
        if( !is_array($roles) || empty($roles) ) return false;
        if( !is_array($allowedRoles) || empty($allowedRoles) ) return false;
        
        foreach($roles as $key=>$val){
            if( ! in_array( $key, $allowedRoles ) )
                 unset( $roles[$key] );   
        }    
        
        return $roles;
    }
    
    
    function adminPageUrl( $page, $html_link=true ){
        global $userMeta;
        
        if( $page == 'fields_editor' ) :
            $link   = 'usermeta';
            $label  = __( 'Fields Editor', $userMeta->name );
        elseif( $page == 'forms_editor' ) :
            $link   = 'user-meta-form-editor';
            $label  = __( 'Forms Editor', $userMeta->name );
        elseif( $page == 'settings' ) :    
            $link = 'user-meta-settings';
            $label  = __( 'Settings', $userMeta->name );
        endif;
        if( !@$link ) return;
        
        $url = admin_url( "admin.php?page=$link" );        
        if( $html_link )
            return "<a href='$url'>$label</a>";
        
        return $url;    
    }    
    
    
    /**
     * Convert content for user provided by %field_name%
     * Supported Extra filter: blog_title, blog_url, avatar, logout_link, admin_link
     * @param $user: WP_User object
     * @param $data: (string) string for convertion
     * @return (string) converted string
     */
    function convertUserContent( $user, $data, $extra=array() ){
        global $userMeta;
        
        preg_match_all( '/\%[a-zA-Z0-9_-]+\%/i', $data, $matches); 
        if( is_array( @$matches[0] ) ){
            $patterns = $matches[0];
            $replacements = array();
            foreach( $patterns as $key => $pattern ){
                $fieldName = strtolower( trim( $pattern, '%' ) );
                if( $fieldName == 'site_title' )
                    $replacements[ $key ] = get_bloginfo( 'name' );
                elseif( $fieldName == 'site_url' )
                    $replacements[ $key ] = site_url();
                elseif( $fieldName == 'avatar' )
                    $replacements[ $key ] = get_avatar( $user->ID );
                elseif( $fieldName == 'login_url' )
                    $replacements[ $key ] = wp_login_url();                    
                elseif( $fieldName == 'logout_url' )
                    $replacements[ $key ] = wp_logout_url();
                elseif( $fieldName == 'lostpassword_url' )
                    $replacements[ $key ] = wp_lostpassword_url();                                         
                elseif( $fieldName == 'admin_url' )
                    $replacements[ $key ] = admin_url();
                elseif( $fieldName == 'activation_url' )
                    $replacements[ $key ] = $userMeta->userActivationUrl( 'activate', $user->ID, false );
                elseif( $fieldName == 'email_verification_url' )
                    $replacements[ $key ] = $userMeta->emailVerificationUrl( $user );
                elseif( $fieldName == 'login_form' )
                    $replacements[ $key ] = $userMeta->lgoinForm();
                elseif( $fieldName == 'lostpassword_form' )
                    $replacements[ $key ] = $userMeta->lostPasswordForm();
                elseif( !empty( $user->$fieldName ) )
                    $replacements[ $key ] = is_array( $user->$fieldName ) ? implode( ',', $user->$fieldName ) : $user->$fieldName;
                else
                    $replacements[ $key ] = '';                                              
            }
            $data = str_replace($patterns, $replacements, $data);
        }    

        return $data;     
    }
    
    
    function loadAllScripts(){
        global $userMeta;

        $userMeta->enqueueScripts( array( 
            'plugin-framework', 
            'user-meta',           
            'jquery-ui-all',
            'fileuploader',
            'wysiwyg',
            'jquery-ui-datepicker',
            'jquery-ui-slider',
            'timepicker',
            'validationEngine',
            'password_strength',
            'placeholder'
        ) );                      
        $userMeta->runLocalization();
    }
    
    
    function getCustomFieldRegex(){
        global $userMeta;
        
        $fields = $userMeta->getData( 'fields' );
        
        $rules = array();
        if ( is_array( $fields ) ) {
            foreach ( $fields as $id => $field ){
                if ( 'custom' == @$field['field_type'] ) {
                    $custom = array();
                    $custom['regex'] = @$field['regex'];
                    $custom['alertText'] = @$field['error_text'];
                    $rules[ 'umCustomField_' . $id ] = $custom;
                }
            }
        }
        
        return json_encode( $rules );
    }
    
    
    function checkPro(){
        global $userMeta;
        $isPro = file_exists( $userMeta->modelsPath . 'enc/umProSupportModelEncrypted.php' ) ? true : false;
        $userMeta->isPro = $isPro;
    }
    
    
    function uploadDir() {
        $dir = apply_filters( 'user_meta_upload_dir', '/uploads/files/' );
        if( empty( $dir ) )
            $dir = '/uploads/files/';
        
        $dir = '/' . trim( $dir, '/' ) . '/';
        
        $path   = WP_CONTENT_DIR . $dir;
        $url    = WP_CONTENT_URL . $dir;
        
        if ( ! file_exists( $path ) && ! is_dir( $path ) ) {
            mkdir( $path, 0777, true );   
            touch( $path . 'index.html' );
        }
        
        return array(
            'path'  => $path,
            'url'   => $url,
            'subdir'=> $dir  
        );   
    }
    
    
    function determinFileDir( $fileSubPath, $checkOnlyOneDir=false ) {
        $file = array();
        
        if ( empty( $fileSubPath ) ) return $file;
        
        // Check file in WP_CONTENT_DIR
        if ( file_exists( WP_CONTENT_DIR . $fileSubPath ) ) {
            $file['path']   = WP_CONTENT_DIR . $fileSubPath;
            $file['url']    = WP_CONTENT_URL . $fileSubPath;
            return $file;
        }
        
        if ( $checkOnlyOneDir )
            return $file;
        
        // UMP backword compatibility
        $uploads    = wp_upload_dir();
        if ( file_exists( $uploads['basedir'] . $fileSubPath ) ) {
            $file['path']   = $uploads['basedir'] . $fileSubPath;
            $file['url']    = $uploads['baseurl'] . $fileSubPath;
            return $file;
        }
        
        // backword compatibility for multisite
        if ( is_multisite() ) {
            $siteurl = get_option( 'siteurl' );
            
            // check main site first
            if ( file_exists( WP_CONTENT_DIR . '/uploads' . $fileSubPath ) ) {
                $file['path']   = WP_CONTENT_DIR . '/uploads' . $fileSubPath;
                $file['url']    = trailingslashit( $siteurl ) . 'wp-content/uploads' . $fileSubPath;
                return $file;
            }
            
            // now check whole network
            foreach ( wp_get_sites() as $site ) {
                if ( file_exists( WP_CONTENT_DIR . "/blogs.dir/{$site['blog_id']}/files" . $fileSubPath ) ) {
                    $file['path']   = WP_CONTENT_DIR . "/blogs.dir/{$site['blog_id']}/files" . $fileSubPath;
                    $file['url']    = trailingslashit( $siteurl ) . "wp-content/blogs.dir/{$site['blog_id']}/files" . $fileSubPath;
                    return $file;
                }         
            }
            
        }
        
        return $file;
    }
        
}
endif;
