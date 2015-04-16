var communityViewModel;
$(document).ready(function(){
    communityViewModel = new communityManagementVM();
    communityViewModel.initCommunity(communityViewModel);
    
    ko.applyBindings(communityViewModel);
});

var ajax_url = "http://sapir.psych.wisc.edu/~yan/Psycho-Project/admin/include/edit-community-functions.php";

var communityManagementVM = function (){
    var self = this;
    
    // private variables here
    self.currentUser = {};
    self.currentIsRoot = ko.observable(false);
    self.allUsers = [];
    self.allPages = ko.observableArray([]);
    self.currentPageNum = ko.observable(1);
    self.currentPage = ko.observableArray([]);
    self.totalPage = ko.observable(1);
    self.totalUsers = ko.observable(0);
    self.pageNum = ko.computed(function(){
        return "page " + this.currentPageNum() + " of " + (this.totalPage() == 0 ? 1 : this.totalPage());
    }, this);
    self.itemNum = ko.computed(function(){
        return ((self.currentPage().length == 0) ? 0 : ((self.currentPageNum() - 1 ) * 10 + 1) ) + " - " + ((self.currentPageNum() * 10 > self.totalUsers()) ? ((self.currentPageNum() - 1) * 10 + self.currentPage().length) : (self.currentPageNum() * 10));
    }, this);
    self.capacity = ko.observable($(".community-capacity").text().trim());
    self.capacityText = ko.computed(function(){
        return "Capacity: " + self.totalUsers() + " / " + self.capacity();   
    });
    self.migrateCommunityPool = ko.observableArray([]);
    self.selectedCommunity = ko.observable({});
    
    self.validCapacity = ko.observable(0);
    
};

communityManagementVM.prototype = function(){
    // private members
    var initCommunity = function(self){
        self.allUsers = [];
        $.ajax({
            type: "POST",
            url: ajax_url,
            cache: false,
            data: {
                request: "get_all_users",
                community_name : $(".community-name").text().trim().replace("Users in ", "")
            }
        }).done(function(result){
            var data = $.parseJSON(result);
            self.totalUsers(data.length);
            for (var i = 0; i < data.length; i ++){
                self.allUsers.push(self.observableUser(data[i]));
            }
            self.paginateUsers(self.allUsers, self);
            self.totalPage(self.allPages().length);
            self.updateCurrentPage(self);
        }).fail(function(){

        });
        self.getMigrateCommunity();
        self.getCurrentAdmin();
    },
        
    observableUser = function(user){
        return {
            id : user._id,
            username : user._username,
            game_cnt: user._game_cnt,
            hovered: ko.observable(false),
            selected: ko.observable(false)
        }
    },
        
    updateCurrentPage = function(self){
        if (self.allPages().length == 0){
            self.currentPage([]);
        }else{
            self.currentPage(self.allPages()[self.currentPageNum() - 1]());
        }
    },
      
    initPopOver = function(el, title, content, trigger){
        el.popover({
            title: title,
            content: content,
            trigger: trigger,
        });
    },
        
    showPopOver = function(el){
        el.popover('show');
    },
        
    hidePopOver = function(el){
        el.popover('hide');
    },
        
    destroyPopOver = function(el){
        el.popover('destroy');
    },
        
    errorInputEffect = function(el){
        el.removeClass("has-success");
        el.addClass("has-error");
    },
        
    successInputEffect = function(el){
        el.removeClass("has-error");
        el.addClass("has-success");
    },
        
    paginateUsers = function(allUsers){
        var page = ko.observableArray([]);
        this.allPages([]);
        for (var i = 0; i < allUsers.length; i ++){
            page.push(allUsers[i]);
            if (i % 10 == 9 || i == allUsers.length-1){
                this.allPages.push(page);
                page = ko.observableArray([]);
            }
        }
        
    },
        
    entryHover = function (data, event){
        data.hovered(!data.hovered());
    },
        
    entryUnhover = function(data, event){
        data.hovered(!data.hovered());
    },
        
    singleSelect = function(data, event){
        for (var i = 0; i < this.allUsers.length; i ++){
            if (this.allUsers[i] !== data){
                this.allUsers[i].selected(false);
            }
        }
        multiSelect(data, event);
    },
    
    multiSelect = function(data, event){
        event.stopPropagation();
        data.selected(!data.selected());
    },
        
    nextPage = function(data, event){
        this.currentPageNum(this.currentPageNum() + 1);
        this.updateCurrentPage(this);
    },
        
    prevPage = function(data, event){
        this.currentPageNum(this.currentPageNum() - 1);
        this.updateCurrentPage(this);
    },
        
    capacityChange = function(data, event){
        this.newCapacity = $(event.target).val();
        if (isNaN(parseInt(this.newCapacity)) || parseInt(this.newCapacity) < this.totalUsers() || parseInt(this.newCapacity) < 0){
            this.validCapacity(-1);
            this.errorInputEffect($(event.target).parent());
            this.destroyPopOver($(event.target).parent());
            this.initPopOver($(event.target).parent(), "Error", "Capacity must be non-negative and greater than current user number", "");
            this.showPopOver($(event.target).parent());
        }else{
            this.validCapacity(1);
            this.successInputEffect($(event.target).parent());
            this.hidePopOver($(event.target).parent());
        }  
    },
        
    updateCapacity = function(){
        $.ajax({
            type: "POST",
            url: ajax_url,
            cache: false,
            data: {
                request: "update_capacity",
                community_name : $(".community-name").text().trim().replace("Users in ", ""),
                new_capacity: this.newCapacity
            }
        }).done(function(result){
            communityViewModel.capacity(communityViewModel.newCapacity);
            showPrompt("success", "You successfully changed capacity to <strong>" + communityViewModel.newCapacity + "</strong>");
        }).fail(function(){

        });
    },
        
    getCurrentAdmin = function(){
        var currentUsername = document.cookie.match(/psy_admin_username[^;]*/)[0].match(/=.*/)[0].match(/[^=]+/)[0];
        var self = communityViewModel;
        $.ajax({
            type: "POST",
            url: ajax_url,
            cache: false,
            data: {
                request: "get_current_user",
                username: currentUsername
            }
        }).done(function(result){
            self.currentUser = $.parseJSON(result)
            self.currentIsRoot(self.currentUser._root == "1");
        }).fail(function(){

        });
    },
        
    edit = function(data, event){
        event.stopPropagation();
        
    },
        
    observableCommunity = function(community){
        return {
            id : community._id,
            name : community._name,
            capacity: community._capacity,
            user_cnt: community._user_cnt,
            hovered: ko.observable(false),
            selected: ko.observable(false)
        }
    },
        
    getMigrateCommunity = function(){
        communityViewModel.migrateCommunityPool([]);
        $.ajax({
            type: "POST",
            url: ajax_url,
            cache: false,
            data: {
                request: "get_migrate_community",
                community_name : $(".community-name").text().trim().replace("Users in ", "")
            }
        }).done(function(result){
            var data = $.parseJSON(result);
            for (var i = 0; i < data.length; i ++){
                data[i].optionText = data[i]._name + "(" + data[i]._user_cnt + "/" + data[i]._capacity + ")";
                communityViewModel.migrateCommunityPool.push(data[i]);
            }
        }).fail(function(){

        });
    },
        
    migrateUser = function(data, event){
        if (typeof this.selectedCommunity() == "undefined"){
            showPrompt("warning", "You must select a community to migrate first");   
        }else{
            var select_cnt = 0;
            var maxMigrateNum = this.selectedCommunity()._capacity - this.selectedCommunity()._user_cnt;
            for (var i = 0; i < this.allUsers.length; i ++){
                if (this.allUsers[i].selected()){
                    select_cnt ++;
                }
            }
            if (select_cnt > maxMigrateNum){
                showPrompt("danger", "You can not migrate more than " + maxMigrateNum + " users to " + this.selectedCommunity()._name + ".");
            }else{
                for (var i = 0; i < this.allUsers.length; i ++){
                    if (this.allUsers[i].selected()){
                        $.ajax({
                            type: "POST",
                            url: ajax_url,
                            cache: false,
                            data: {
                                request: "migrate_user",
                                src_community: $(".community-name").text().trim().replace("Users in ", ""),
                                dest_community : this.selectedCommunity()._name,
                                username: this.allUsers[i].username
                            }
                        }).done(function(result){
                            communityViewModel.initCommunity(communityViewModel);
                            communityViewModel.getMigrateCommunity();
                        }).fail(function(){

                        });
                    }
                }
            }
        }
    };
        
    return {
        // public members (functions)  
        initCommunity           : initCommunity,
        initPopOver             : initPopOver,
        showPopOver             : showPopOver,
        hidePopOver             : hidePopOver,
        destroyPopOver          : destroyPopOver,
        errorInputEffect        : errorInputEffect,
        successInputEffect      : successInputEffect,
        paginateUsers           : paginateUsers,
        observableUser          : observableUser,
        updateCurrentPage       : updateCurrentPage,
        entryHover              : entryHover,
        entryUnhover            : entryUnhover,
        singleSelect            : singleSelect,
        multiSelect             : multiSelect,
        nextPage                : nextPage,
        prevPage                : prevPage,
        capacityChange          : capacityChange,
        getCurrentAdmin         : getCurrentAdmin,
        updateCapacity          : updateCapacity,
        edit                    : edit,
        observableCommunity     : observableCommunity,
        getMigrateCommunity     : getMigrateCommunity,
        migrateUser             : migrateUser
    }
}();

function arrayObjectIndexOf(myArray, searchTerm, property) {
    for(var i = 0, len = myArray.length; i < len; i++) {
        if (myArray[i][property] === searchTerm) return i;
    }
    return -1;
}

function showPrompt(type, message){
    var el;
    $(".alert").remove();
    el = ((type == "success") ? "<div class='alert alert-success'>" : (type == "warning") ? "<div class='alert alert-warning'>" : (type == "danger") ? "<div class='alert alert-danger'>" : "<div class='alert alert-info'>") + message + "</div>";
    $(el).insertAfter($("#p_header"));
    setTimeout(function(){
        $(".alert").css({"top" : "60px"});
        setTimeout(function(){
            $(".alert").css({"top" : "0px"});
            setTimeout(function(){
                $(".alert").remove();
            }, 200);
        }, 2000);
    }, 10);
}