<div class="col-xs-18 search_result partial_view">
   <div class="col-xs-18">
   		<div class="col-xs-18" >
   			<input type="text" class="form-control input-sm get_input" ng-model="reviewForm.Name" id="service_finder" placeholder="Business Name" name="">
    	</div>
        <div class="col-xs-18" ng-show="formreveal==0">
   			<input type="text" class="form-control input-sm get_input" ng-model="reviewForm.Location" placeholder="Location" name="">
    	</div>
        <div class="col-xs-18" ng-show="formreveal==0">
   			<select class="col-xs-18 nopadding input-sm" id="state" ng-model="reviewForm.Local_government"  ng-options="option.value as option.name for option in localList"></select>
    	</div>
        <div class="col-xs-18" ng-show="formreveal==0">
   			<select class="col-xs-18 nopadding input-sm" id="state" ng-model="reviewForm.State" ng-options="option.value as option.name for option in stateList"></select>
    	</div>
        <div class="col-xs-18" ng-show="formreveal==0">
   			<select class="col-xs-18 nopadding input-sm" id="state" ng-model="reviewForm.Industry" ng-options="option.value as option.name for option in Industry"></select>
    	</div>
        
        <div class="col-xs-18" ng-show="formreveal>=1">
        	<span><b>{{Sdata[0].service_name}}</b></span>
            <span class="col-xs-18">{{Sdata[0].service_ind}}</span>
            <span class="col-xs-18">{{Sdata[0].service_address}}</span>
            <span class="col-xs-18">{{Sdata[0].service_localg}}</span>
            <span class="col-xs-18">{{Sdata[0].service_state}}</span>
            
        </div>
        <div class="col-xs-18" ng-if="formreveal>=1"></div>
        <div class="col-xs-18" ng-repeat="cat in Categories">
        	<label>{{cat.type_name}} &nbsp; &nbsp; &nbsp; <span ng-if="cat.rate">Rated {{cat.rate}}/5 from {{cat.t_rate}} Reviews</span></label>
            <div class="col-xs-18" ng-init="get_post(cat.type_no, 'st', $index)">
            	<div class="col-xs-18" ng-if="cat.post==null">No Reviews yet, be the first to review this category</div>
                <div class="col-xs-18" ng-if="cat.post!=null">
                	<span class="col-xs-18"><b>Recent Reviews received</b>
                    	
                    </span>
                    <span class="col-xs-18" ng-repeat="poster in cat.post">
                    	<span>{{poster[0].p_cont}} | </span> 
                        <span user-details user-id="poster[0].p_author"></span>
                        <span> | {{poster[0].dt | posttime}}</span>
                    </span>
                    <span class="col-xs-18">View More</span>                    
                </div>
                <div class="col-xs-8 btn-xs btn get_button_primary" ng-show="post[$index].newp=='N'" ng-click="post[$index].newp='Y'">Add A Review</div>
            </div>
            <div class="col-xs-18" ng-show="post[$index].newp=='Y'">
            	<span class="icon-star" ng-class="{r_active: post[$index].newr>=1}" ng-click="post[$index].newr=1"></span>
                <span class="icon-star" ng-class="{r_active: post[$index].newr>=2}" ng-click="post[$index].newr=2"></span>
                <span class="icon-star" ng-class="{r_active: post[$index].newr>=3}" ng-click="post[$index].newr=3"></span>
                <span class="icon-star" ng-class="{r_active: post[$index].newr>=4}" ng-click="post[$index].newr=4"></span>
                <span class="icon-star" ng-class="{r_active: post[$index].newr>=5}" ng-click="post[$index].newr=5"></span>
                <div class="col-xs-18">
                    <textarea class="col-xs-18 nopadding input-sm" id="description" ng-model="post[$index].newc"  placeholder="Review Desciption"></textarea>
                </div>
                <div class="col-xs-8 col-xs-offset-10 btn-xs btn get_button_primary" ng-show="post[$index].newp=='Y'" ng-click="post[$index].newp='N'">Cancel Review</div>
            </div>
            <br />
        </div>
<!--        <div class="col-xs-18">
   			<label> Upload Picture (less than 2mb each) Jpeg, PNG, Bitmap and GIF </label>
    	</div>-->
        <div class="col-xs-18 nopadding">
            <div class="col-md-18 nopadding"  ng-switch on="getUdata[0].status">
            	<button class="col-xs-18 btn-sm btn get_button_primary" ng-click="openRegister()" ng-switch-when="Register" style="margin:15px 0 0">Login OR Register</button>
                <button class="col-xs-18 btn-sm btn get_button_primary" ng-switch-when="Logged_in" ng-click="postReview(getUdata[1].userId, action)" style="margin:15px 0 0">Post Your Reviews</button>
             </div>
        </div>
   </div>
</div>