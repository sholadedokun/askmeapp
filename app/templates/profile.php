<div class="col-xs-18">
	<div class="col-xs-5" style="background:#eee"> Profile <br /><br />Pix</div>
    <div class="col-xs-13">
    	<span class="col-xs-18">{{userD[1].fname}} {{userD[1].lname}}</span>
        <span class="col-xs-18">{{userD[1].rank}}</span>
        <span class="col-xs-18">{{userD[1].joined | posttime}}</span>
        <span class="col-xs-18" ng-init="get_post(userD[1].userId)">{{totalReview}} Total Reviews</span>
        
    </div>
    <div class="col-xs-18" ng-show="totalReview>0">
    	<div class="col-xs-18 nopadding" ng-repeat="review in reviews" ng-init="get_review(review.s_id, $index)">
        	<br /> <br />
            <div class="col-xs-3">Business <br /> <br /> Picture</div>
            <div class="col-xs-offset-2 col-xs-13" >
                <span class="col-xs-18"><label><h4>{{Business[$index][0].service_name}}</h4></label></span>
                <div class="col-xs-18" ng-repeat="bus in Business[$index]">
                	<div class="col-xs-18"><b>{{bus['category'][0].category_name}}</b></div>
                	<div class="col-xs-18" ng-repeat="cat in bus['category']">
                        <span class="col-xs-18" ng-if="cat.content_type=='.r.'" >
                            <span class="icon-star" ng-repeat="n in getNumber(cat.postcontent) track by $index"></span>
                        </span>
                        <span class="col-xs-18" ng-if="cat.content_type=='.c.'">{{cat.postcontent}}</span>
                    </div>
                </div>
            </div>
            <div class="col-xs-8 btn-xs btn get_button_primary">Update Review</div>
        </div>
        <br />
    	<br />
    </div>
    
    <div class="col-xs-18">
    	<div class="col-xs-8 btn-xs btn get_button_primary" ng-click="editP='Y'">Change Password</div>
        <div class="col-xs-18" ng-show="editP=='Y'">
        	<input class="col-xs-18" type="text" ng-model="pass.oldP" placeholder="Old Password" />
            <input class="col-xs-18" type="text" ng-model="pass.oldn" placeholder="New Password" />
        	<input class="col-xs-18" type="text" ng-model="pass.oldnr" placeholder="Re-type New Password" />
            <span class="col-xs-18" ng-show='pass.oldn == pass.oldnr'>Password Okay</span>
            <div class="col-xs-18 btn-xs btn get_button_primary" ng-click="updatePass(pass)">Change</div>
        </div>
    </div>    
</div>