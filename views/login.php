<?php
	
$output  = '

<ul class="mabtabs">
  <li><a href="#mabtab1">'.__('Login', 'mobile-app-builder').'</a></li>
  <li><a href="#mabtab2">'.__('Register', 'mobile-app-builder').'</a></li>
</ul>
<div id="mabtab1">

<div class="mab-login-from-wrap">
                            <form class="forms" method="post" id="mab_login_form">
                                <fieldset>
                                    <legend>'.__('Login','mobile-app-builder').'</legend>
                                    <section>
                                        <label>'.__('Username','mobile-app-builder').'</label>
                                        <input type="text" name="mab_user_login" class="width-6"  />
                                    </section>
                                    <section>
                                        <label>'.__('Password','mobile-app-builder').'</label>
                                        <input type="password" name="mab_user_password" class="width-6"  />
                                    </section>
                                    <section>
								        <label class="checkbox">
								        <input type="checkbox" name="mab_rememberme" value="true"> '.__('Remember me','mobile-app-builder').'</label>
								    </section>
                                    <section>
                                        <button type="primary" class="mab_login_btn">'.__('Log in','mobile-app-builder').'</button>
                                        <img src="'.MAB_DIR_URL.'static/img/ajax-loading.gif" id="mab_loader_login" style="display:none;">
                                   
                                    </section>
                                    <section style="display:none" class="mab_response_msg_login">
                                    	<div class="alert"></div>
                                    </section>
                                </fieldset>
                            </form>
                        </div> 
                         </div> 
    <div id="mabtab2">                    
                       <div class="mab-register-from-wrap">
                            <form class="forms" method="post" id="mab_register_form">
                                <fieldset>
                                    <legend>'.__('Register','mobile-app-builder').'</legend>
                                    <section>
                                    <label for="reg-name">'.__('Username','mobile-app-builder').' <span class="req">*</span> </label>
                                    <input type="text" name="reg_uname" class="width-6" id="reg-name" required/>
                                </section>
                                <section>
                                    <label>'.__('Email','mobile-app-builder').' <span class="req">*</span> </label>
                                    <input type="email" name="reg_email" class="width-6" required/>
                                </section>
                                <section>
                                    <label>'.__('Password','mobile-app-builder').' <span class="req">*</span> </label>
                                    <input type="password" name="reg_password" class="width-6" required />
                                </section>
                                <section>
                                    <label>'.__('First Name','mobile-app-builder').' <span class="req">*</span></label>
                                    <input type="text" name="reg_fname" class="width-6" />
                                </section>
                                <section>
                                    <label>'.__('Last Name','mobile-app-builder').' <span class="req">*</span></label>
                                    <input type="text" name="reg_lname" class="width-6" />
                                </section>
                                <section>
                                    <label>'.__('Website','mobile-app-builder').'</label>
                                    <input type="text" name="reg_website" class="width-6" />
                                </section>
                                <section>
                                    <label>'.__('Nickname','mobile-app-builder').'</label>
                                    <input type="text" name="reg_nickname" class="width-6" />
                                </section>
                                
                                <section>
                                    <button type="primary" class="mab_reg_btn">'.__('Register','mobile-app-builder').'</button>
                                    <img src="'.MAB_DIR_URL.'static/img/ajax-loading.gif" id="mab_loader_reg" style="display:none;">
                                </section>
                                <section style="display:none" class="mab_response_msg">
                                    <div class="alert"></div>
                                </section>
                                </fieldset>
                            </form>
                             </div> 
                        </div>
                        
                        '.wp_nonce_field('fepnonce_action', 'fepnonce').'';    
           
            return $output;