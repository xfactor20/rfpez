
(function() {
  var App, AppView, Collaborator, CollaboratorList, CollaboratorView, Collaborators;
  Collaborator = Backbone.Model.extend({
    validate: function(attrs) {
      var errors;
      errors = [];
      if (!attrs.User.email) {
        return true;
      } else if (!attrs.User.email.match(/.gov$/i)) {
        errors.push("Sorry, .gov addresses only");
      } else if (!attrs.id && Collaborators.existing_emails().indexOf(attrs.User.email.toLowerCase()) !== -1) {
        errors.push("That collaborator already exists.");
      }
      if (errors.length > 0) {
        App.trigger('errorAdding', errors);
        return errors;
      }
    },
    defaults: function() {
      return {
        owner: false
      };
    },
    clear: function() {
      return this.destroy();
    }
  });
  CollaboratorList = Backbone.Collection.extend({
    existing_emails: function() {
      return this.map(function(c) {
        return c.attributes.User.email.toLowerCase();
      });
    },
    model: Collaborator
  });
  CollaboratorView = Backbone.View.extend({
    tagName: "tr",
    template: _.template("<td class=\"email\"><%- User.email %></td>\n<td>\n  <% if (pivot.owner === \"1\") { %>\n    <i class=\"icon-star\"></i>\n  <% } %>\n</td>\n<td>\n  <span class=\"not-user-<%- User.id %> only-user only-user-<%- owner_id %>\">\n    <% if (pivot.owner !== \"1\") { %>\n      <button class=\"btn btn-danger\">Remove</button>\n    <% } else { %>\n      Can't remove the owner.\n    <% } %>\n  </span>\n  <span class=\"only-user only-user-<%- User.id %>\">\n    That's you!\n  </span>\n</td>"),
    events: {
      "click .btn.btn-danger": "clear"
    },
    initialize: function() {
      this.model.bind("change", this.render, this);
      return this.model.bind("destroy", this.remove, this);
    },
    render: function() {
      this.$el.html(this.template(_.extend(this.model.toJSON(), {
        owner_id: App.options.owner_id
      })));
      return this;
    },
    clear: function() {
      return this.model.clear();
    }
  });
  AppView = Backbone.View.extend({
    initialize: function() {
      Collaborators.bind('add', this.addOne, this);
      Collaborators.bind('reset', this.reset, this);
      Collaborators.bind('all', this.render, this);
      this.bind('errorAdding', this.showError);
      return $("#add-collaborator-form").submit(this.addNew);
    },
    addNew: function(e) {
      var email;
      e.preventDefault();
      email = $("#add-collaborator-form input[name=email]").val();
      $("#add-collaborator-form input[name=email]").val('');
      return Collaborators.create({
        User: {
          email: email
        },
        pivot: {
          owner: 0
        }
      }, {
        error: function(obj, err) {
          return obj.clear();
        }
      });
    },
    showError: function(errors) {
      return $("#add-collaborator-form button").flash_button_message("warning", errors[0]);
    },
    reset: function() {
      $("#collaborators-tbody").html('');
      return this.addAll();
    },
    render: function() {},
    addOne: function(collaborator) {
      var html, view;
      view = new CollaboratorView({
        model: collaborator
      });
      html = view.render().el;
      return $("#collaborators-tbody").append(html);
    },
    addAll: function() {
      return Collaborators.each(this.addOne);
    }
  });
  App = false;
  Collaborators = false;
  return Rfpez.Backbone.Collaborators = function(project_id, owner_id, initialModels) {
    var initialCollection;
    Collaborators = new CollaboratorList;
    initialCollection = Collaborators;
    App = new AppView({
      collection: initialCollection,
      owner_id: owner_id
    });
    initialCollection.reset(initialModels);
    initialCollection.url = "/projects/" + project_id + "/collaborators";
    return App;
  };
})();
