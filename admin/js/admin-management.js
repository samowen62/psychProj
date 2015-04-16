var adminViewModel;
$(document).ready(function(){
    adminViewModel = new adminManagementVM();
    adminViewModel.initAdminUsers(adminViewModel);
    
    ko.applyBindings(adminViewModel);
});

var ajax_url = "http://sapir.psych.wisc.edu/~yan/Psycho-Project/admin/include/admin-management-functions.php";

var adminManagementVM = function (){
    var self = this;
    
    // private variables here
    self.currentUser = {};
    self.currentIsRoot = ko.observable(false);
    self.adminUsers = [];
    self.allPages = ko.observableArray([]);
    self.currentPageNum = ko.observable(1);
    self.currentPage = ko.observableArray([]);
    self.totalPage = ko.observable(1);
    self.totalAdminUsers = ko.observable(0);
    self.pageNum = ko.computed(function(){
        return "page " + this.currentPageNum() + " of " + this.totalPage();
    }, this);
    self.itemNum = ko.computed(function(){
        return ((self.currentPage().length == 0) ? 0 : ((self.currentPageNum() - 1 ) * 10 + 1) ) + " - " + ((self.currentPageNum() * 10 > self.totalAdminUsers()) ? ((self.currentPageNum() - 1) * 10 + self.currentPage().length) : (self.currentPageNum() * 10));
    }, this);
    // for create users
    self.validUsername = ko.observable(0);
    self.validPassword = ko.observable(0);
    self.validConfirmPassword = ko.observable(0);
    self.isRoot = 0;
    self.newUsername = "";
    self.newPassword = "";
};

adminManagementVM.prototype = function(){
    // private members
    var initAdminUsers = function(self){
        $.ajax({
            type: "POST",
            url: ajax_url,
            cache: false,
            data: {
                request: "get_admin_users"
            }
        }).done(function(result){
            var data = $.parseJSON(result);
            self.totalAdminUsers(data.length);
            for (var i = 0; i < data.length; i ++){
                self.adminUsers.push(self.observableAdminUser(data[i]));
            }
            self.paginateUsers(self.adminUsers, self);
            self.totalPage(self.allPages().length);
            self.updateCurrentPage(self);
        }).fail(function(){

        });
        
        self.getCurrentAdmin();
    },
        
    observableAdminUser = function(user){
        return {
            id : user._id,
            username : user._username,
            root: ko.observable(user._root == 1),
            hovered: ko.observable(false),
            selected: ko.observable(false)
        }
    },
        
    updateCurrentPage = function(self){
        self.currentPage(self.allPages()[self.currentPageNum() - 1]());
    },
        
    adminUsernameChange = function(data, event){
        this.newUsername = $(event.target).val();
        var duplicate = false;
        if (this.newUsername.length < 6 || this.newUsername.length > 20){
            this.validUsername(-1);
            this.errorInputEffect($(event.target).parent());
            this.destroyPopOver($(event.target).parent());
            this.initPopOver($(event.target).parent(), "Error", "Length must be between 6 and 20", "");
            this.showPopOver($(event.target).parent());
        }else{
            for (var i = 0; i < this.adminUsers.length; i ++){
                if (this.newUsername == this.adminUsers[i].username){
                    duplicate = true;
                }
            }
            if (duplicate){
                this.validUsername(-1);
                this.errorInputEffect($(event.target).parent());
                this.destroyPopOver($(event.target).parent());
                this.initPopOver($(event.target).parent(), "Error", "Username already exists", "");
                this.showPopOver($(event.target).parent());
            }else{
                this.validUsername(1);
                this.successInputEffect($(event.target).parent());
                this.hidePopOver($(event.target).parent());
            }
        }
    },
        
    passwordChange = function(data, event){
        this.newPassword = $(event.target).val();
        if (this.newPassword.length < 6 || this.newPassword.length > 30){
            this.validPassword(-1);
            this.errorInputEffect($(event.target).parent());
            this.destroyPopOver($(event.target).parent());
            this.initPopOver($(event.target).parent(), "Error", "Length must be between 6 and 30", "");
            this.showPopOver($(event.target).parent());
        }else{
            this.validPassword(1);
            this.successInputEffect($(event.target).parent());
            this.hidePopOver($(event.target).parent());
        }
        $(event.target).parent().parent().find("#confirmPassword").keyup();
    },
        
    confirmPasswordChange = function(data, event){
        var confirmPassword = $(event.target).val();
        if (confirmPassword.length < 6 || confirmPassword.length > 30){
            this.validConfirmPassword(-1);
            this.errorInputEffect($(event.target).parent());
            this.destroyPopOver($(event.target).parent());
            this.initPopOver($(event.target).parent(), "Error", "Length must be between 6 and 30", "");
            this.showPopOver($(event.target).parent());
        }else{
            if (confirmPassword != this.newPassword){
                this.validConfirmPassword(-1);
                this.errorInputEffect($(event.target).parent());
                this.destroyPopOver($(event.target).parent());
                this.initPopOver($(event.target).parent(), "Error", "Unmatched password", "");
                this.showPopOver($(event.target).parent());
            }else{
                this.validConfirmPassword(1);
                this.successInputEffect($(event.target).parent());
                this.hidePopOver($(event.target).parent());
            }
        }
    },
        
    rootOptionChange = function(data, event){
        var root = $(event.target).prop("checked");   
        this.isRoot = root ? 1 : 0;
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
        
    addUser = function(data, event){
        var self = this;
        $.ajax({
            type: "POST",
            url: ajax_url,
            cache: false,
            data: {
                request: "add_admin_user",
                username: this.newUsername,
                password: this.newPassword,
                root: this.isRoot
            }
        }).done(function(result){
            var newUser = self.observableAdminUser({_id: parseInt(self.adminUsers[self.adminUsers.length-1].id) + 1, _username: self.newUsername, _root: 0});
            self.adminUsers.push(newUser);
            self.paginateUsers(self.adminUsers, self);
            self.totalPage(self.allPages().length);
            self.updateCurrentPage(self);
            showPrompt("success", "You successfully added new admin user <strong>" + self.newUsername + "</strong>");
        }).fail(function(){

        });
        $(event.target).parent().find("input").each(function(){
                                                        $(this).val("");
                                                        $(this).parent().removeClass("has-success");
                                                    });
        $("#newUsername").focus();
        this.validUsername(0);
        this.validPassword(0);
        this.validConfirmPassword(0);
        this.isRoot = 0;
        $(".checkbox input").prop("checked", false);
    },
        
    paginateUsers = function(users){
        var page = ko.observableArray([]);
        this.allPages([]);
        for (var i = 0; i < users.length; i ++){
            page.push(users[i]);
            if (i % 10 == 9 || i == users.length-1){
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
        for (var i = 0; i < this.adminUsers.length; i ++){
            if (this.adminUsers[i] !== data){
                this.adminUsers[i].selected(false);
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
        
    changeRoot = function(data, event){
        event.stopPropagation();
        $.ajax({
            type: "POST",
            url: ajax_url,
            cache: false,
            data: {
                request: "change_root",
                username: data.username,
                root: data.root() ? 1 : 0
            }
        }).done(function(result){
            //console.log(result);
        }).fail(function(){

        });
        return true;
    },
        
    singleDelete = function(data, event){
        event.stopPropagation();
        var self = adminViewModel;
        if (!(self.currentUser._username == data.username)){
            $.ajax({
                type: "POST",
                url: ajax_url,
                cache: false,
                data: {
                    request: "delete_user",
                    username: data.username
                }
            }).done(function(result){
                self.adminUsers.splice(arrayObjectIndexOf(self.adminUsers, data.username, "username"), 1);
                self.paginateUsers(self.adminUsers, self);
                self.totalPage(self.allPages().length);
                if(self.currentPageNum() > self.totalPage()){
                    self.currentPageNum(self.totalPage());
                }
                self.updateCurrentPage(self);
            }).fail(function(){

            });
        }else{
            showPrompt("danger", "You cannot delete yourself");   
        }
        return true;
    },
        
    multiDelete = function(data, event){
        event.stopPropagation();
        var self = adminViewModel;
        for (var i = 0; i < self.adminUsers.length; i ++){
            if (self.adminUsers[i].selected()){
                self.singleDelete(self.adminUsers[i], event);   
            }
        }
    },
        
    getCurrentAdmin = function(){
        var currentUsername = document.cookie.match(/psy_admin_username[^;]*/)[0].match(/=.*/)[0].match(/[^=]+/)[0];
        var self = adminViewModel;
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
            self.currentIsRoot(self.observableAdminUser(self.currentUser).root());
        }).fail(function(){

        });
    };
        
    return {
        // public members (functions)  
        initAdminUsers          : initAdminUsers,
        adminUsernameChange     : adminUsernameChange,
        passwordChange          : passwordChange,
        confirmPasswordChange   : confirmPasswordChange,
        rootOptionChange        : rootOptionChange,
        initPopOver             : initPopOver,
        showPopOver             : showPopOver,
        hidePopOver             : hidePopOver,
        destroyPopOver          : destroyPopOver,
        errorInputEffect        : errorInputEffect,
        successInputEffect      : successInputEffect,
        addUser                 : addUser,
        paginateUsers           : paginateUsers,
        observableAdminUser     : observableAdminUser,
        updateCurrentPage       : updateCurrentPage,
        entryHover              : entryHover,
        entryUnhover            : entryUnhover,
        singleSelect            : singleSelect,
        multiSelect             : multiSelect,
        nextPage                : nextPage,
        prevPage                : prevPage,
        changeRoot              : changeRoot,
        singleDelete            : singleDelete,
        multiDelete             : multiDelete,
        getCurrentAdmin         : getCurrentAdmin
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