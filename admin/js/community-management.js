var communityViewModel;
$(document).ready(function(){
    communityViewModel = new communityManagementVM();
    communityViewModel.initCommunities(communityViewModel);
    
    ko.applyBindings(communityViewModel);
});

var ajax_url = "http://sapir.psych.wisc.edu/~yan/Psycho-Project/admin/include/community-management-functions.php";

var communityManagementVM = function (){
    var self = this;
    
    // private variables here
    self.currentUser = {};
    self.currentIsRoot = ko.observable(false);
    self.communities = [];
    self.allPages = ko.observableArray([]);
    self.currentPageNum = ko.observable(1);
    self.currentPage = ko.observableArray([]);
    self.totalPage = ko.observable(1);
    self.totalCommunities = ko.observable(0);
    self.pageNum = ko.computed(function(){
        return "page " + this.currentPageNum() + " of " + this.totalPage();
    }, this);
    self.itemNum = ko.computed(function(){
        return ((self.currentPage().length == 0) ? 0 : ((self.currentPageNum() - 1 ) * 10 + 1) ) + " - " + ((self.currentPageNum() * 10 > self.totalCommunities()) ? ((self.currentPageNum() - 1) * 10 + self.currentPage().length) : (self.currentPageNum() * 10));
    }, this);
    // for create communities
    self.validCommunityName = ko.observable(0);
    self.validCapacity = ko.observable(0);
};

communityManagementVM.prototype = function(){
    // private members
    var initCommunities = function(self){
        $.ajax({
            type: "POST",
            url: ajax_url,
            cache: false,
            data: {
                request: "get_communities"
            }
        }).done(function(result){
            var data = $.parseJSON(result);
            self.totalCommunities(data.length);
            for (var i = 0; i < data.length; i ++){
                self.communities.push(self.observableCommunity(data[i]));
            }
            self.paginateCommunities(self.communities, self);
            self.totalPage(self.allPages().length);
            self.updateCurrentPage(self);
        }).fail(function(){

        });
        
        self.getCurrentAdmin();
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
        
    updateCurrentPage = function(self){
        self.currentPage(self.allPages()[self.currentPageNum() - 1]());
    },
        
    communityNameChange = function(data, event){
        this.newCommunityName = $(event.target).val();
        var duplicate = false;
        if (this.newCommunityName.length < 6 || this.newCommunityName.length > 20){
            this.validCommunityName(-1);
            this.errorInputEffect($(event.target).parent());
            this.destroyPopOver($(event.target).parent());
            this.initPopOver($(event.target).parent(), "Error", "Length must be between 6 and 20", "");
            this.showPopOver($(event.target).parent());
        }else{
            for (var i = 0; i < this.communities.length; i ++){
                if (this.newCommunityName == this.communities[i].name){
                    duplicate = true;
                }
            }
            if (duplicate){
                this.validCommunityName(-1);
                this.errorInputEffect($(event.target).parent());
                this.destroyPopOver($(event.target).parent());
                this.initPopOver($(event.target).parent(), "Error", "Community already exists", "");
                this.showPopOver($(event.target).parent());
            }else{
                this.validCommunityName(1);
                this.successInputEffect($(event.target).parent());
                this.hidePopOver($(event.target).parent());
            }
        }
    },
        
    newCapacityChange = function(data, event){
        this.newCapacity = $(event.target).val();
        if (isNaN(parseInt(this.newCapacity)) || parseInt(this.newCapacity) <= 0){
            this.validCapacity(-1);
            this.errorInputEffect($(event.target).parent());
            this.destroyPopOver($(event.target).parent());
            this.initPopOver($(event.target).parent(), "Error", "Capacity must be positive", "");
            this.showPopOver($(event.target).parent());
        }else{
            this.validCapacity(1);
            this.successInputEffect($(event.target).parent());
            this.hidePopOver($(event.target).parent());
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
        
    addCommunity = function(data, event){
        var self = this;
        $.ajax({
            type: "POST",
            url: ajax_url,
            cache: false,
            data: {
                request: "add_community",
                name: this.newCommunityName,
                capacity: this.newCapacity
            }
        }).done(function(result){
            var newCommunity = self.observableCommunity({_id: parseInt(self.communities[self.communities.length-1].id) + 1, _name: self.newCommunityName, _capacity: self.newCapacity, _user_cnt: 0});
            self.communities.push(newCommunity);
            self.paginateCommunities(self.communities, self);
            self.totalPage(self.allPages().length);
            self.updateCurrentPage(self);
            showPrompt("success", "You successfully added new community: <strong>" + self.newCommunityName + "</strong>");
        }).fail(function(){

        });
        $(event.target).parent().find("input").each(function(){
                                                        $(this).val("");
                                                        $(this).parent().removeClass("has-success");
                                                    });
        $("#newCommunityName").focus();
        this.validCommunityName(0);
        this.validCapacity(0);
    },
        
    paginateCommunities = function(communities){
        var page = ko.observableArray([]);
        this.allPages([]);
        for (var i = 0; i < communities.length; i ++){
            page.push(communities[i]);
            if (i % 10 == 9 || i == communities.length-1){
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
        for (var i = 0; i < this.communities.length; i ++){
            if (this.communities[i] !== data){
                this.communities[i].selected(false);
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
        var self = communityViewModel;
        if (data.user_cnt > 0){
            showPrompt("warning", "You cannot delete non-empty communities, please migrate all users to other communities first.");
        }else{
            $.ajax({
                type: "POST",
                url: ajax_url,
                cache: false,
                data: {
                    request: "delete_community",
                    name: data.name
                }
            }).done(function(result){
                self.communities.splice(arrayObjectIndexOf(self.communities, data.name, "name"), 1);
                self.paginateCommunities(self.communities, self);
                self.totalPage(self.allPages().length);
                if(self.currentPageNum() > self.totalPage()){
                    self.currentPageNum(self.totalPage());
                }
                self.updateCurrentPage(self);
            }).fail(function(){

            });
        }
        return true;
    },
        
    multiDelete = function(data, event){
        event.stopPropagation();
        var self = communityViewModel;
        for (var i = 0; i < self.communities.length; i ++){
            if (self.communities[i].selected()){
                self.singleDelete(self.communities[i], event);   
            }
        }
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
        var el = $(event.target);
        el.find('.edit-form').submit();
    };
        
    return {
        // public members (functions)  
        initCommunities         : initCommunities,
        communityNameChange     : communityNameChange,
        newCapacityChange       : newCapacityChange,
        initPopOver             : initPopOver,
        showPopOver             : showPopOver,
        hidePopOver             : hidePopOver,
        destroyPopOver          : destroyPopOver,
        errorInputEffect        : errorInputEffect,
        successInputEffect      : successInputEffect,
        addCommunity            : addCommunity,
        paginateCommunities     : paginateCommunities,
        observableCommunity     : observableCommunity,
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
        getCurrentAdmin         : getCurrentAdmin,
        edit                    : edit
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