(function(name, definition) {
    if (typeof module != 'undefined') {
      module.exports = definition();
    } else if (typeof define == 'function' && typeof define.amd == 'object') {
      define(definition);
    } else {
      this[name] = definition();
    }
  }('Router', function() {
  return {
    routes: [{"uri":"_debugbar\/open","name":"debugbar.openhandler"},{"uri":"_debugbar\/clockwork\/{id}","name":"debugbar.clockwork"},{"uri":"_debugbar\/assets\/stylesheets","name":"debugbar.assets.css"},{"uri":"_debugbar\/assets\/javascript","name":"debugbar.assets.js"},{"uri":"_debugbar\/cache\/{key}\/{tags?}","name":"debugbar.cache.delete"},{"uri":"login","name":"login"},{"uri":"logout","name":"logout"},{"uri":"register","name":"register"},{"uri":"password\/reset","name":"password.request"},{"uri":"password\/email","name":"password.email"},{"uri":"password\/reset\/{token}","name":"password.reset"},{"uri":"password\/reset","name":"password.update"},{"uri":"password\/confirm","name":"password.confirm"},{"uri":"dashboard","name":"admin.home"},{"uri":"dashboard\/editor\/templates","name":"admin.editor.templates"},{"uri":"dashboard\/page\/{uuid?}","name":"admin.page.home"},{"uri":"dashboard\/page\/edit\/{locale}\/{uuid?}","name":"admin.page.edit"},{"uri":"dashboard\/page\/store","name":"admin.page.save"},{"uri":"dashboard\/page\/delete","name":"admin.page.delete"},{"uri":"dashboard\/page\/sortable","name":"admin.page.sortable"},{"uri":"dashboard\/navigation","name":"admin.navigation.home"},{"uri":"dashboard\/navigation\/edit\/{uuid?}","name":"admin.navigation.edit"},{"uri":"dashboard\/navigation\/store","name":"admin.navigation.store"},{"uri":"dashboard\/navigation\/delete","name":"admin.navigation.delete"},{"uri":"dashboard\/navigation\/items\/{uuid}","name":"admin.navigation.items.home"},{"uri":"dashboard\/navigation\/items\/{uuid}\/edit\/{locale?}\/{navigation_item?}","name":"admin.navigation.items.edit"},{"uri":"dashboard\/navigation\/items\/store","name":"admin.navigation.items.store"},{"uri":"dashboard\/navigation\/items\/delete","name":"admin.navigation.items.delete"},{"uri":"dashboard\/navigation\/items\/sortable","name":"admin.navigation.items.sortable"},{"uri":"dashboard\/navigation\/items\/ajax\/objects-type","name":"admin.navigation.items.objects"},{"uri":"dashboard\/finder\/{uuid?}","name":"admin.finder.home"},{"uri":"dashboard\/finder\/file\/edit\/{locale}\/{uuid}","name":"admin.finder.file.edit"},{"uri":"dashboard\/finder\/upload\/editor","name":"admin.finder.editor.upload"},{"uri":"dashboard\/finder\/upload","name":"admin.finder.upload"},{"uri":"dashboard\/finder\/delete","name":"admin.finder.delete"},{"uri":"dashboard\/finder\/sortable\/{uuid?}","name":"admin.finder.sortable"},{"uri":"dashboard\/finder\/store","name":"admin.finder.file.store"},{"uri":"dashboard\/finder\/folder\/create\/{uuid}","name":"admin.finder.folder.create"},{"uri":"dashboard\/finder\/folder\/create","name":"admin.finder.folder.store"},{"uri":"dashboard\/catalog","name":"admin.catalog.home"},{"uri":"dashboard\/catalog\/category\/{uuid?}","name":"admin.catalog.category.home"},{"uri":"dashboard\/catalog\/category\/edit\/{locale}\/{uuid?}","name":"admin.catalog.category.edit"},{"uri":"dashboard\/catalog\/category\/categories","name":"admin.catalog.category.categories"},{"uri":"dashboard\/catalog\/category\/store","name":"admin.catalog.category.store"},{"uri":"dashboard\/catalog\/category\/delete","name":"admin.catalog.category.delete"},{"uri":"dashboard\/catalog\/category\/sortable","name":"admin.catalog.category.sortable"},{"uri":"dashboard\/catalog\/feature","name":"admin.catalog.feature.home"},{"uri":"dashboard\/catalog\/feature\/edit\/{locale}\/{uuid?}","name":"admin.catalog.feature.edit"},{"uri":"dashboard\/catalog\/feature\/page\/edit\/{locale}\/{uuid?}","name":"admin.catalog.feature.page.edit"},{"uri":"dashboard\/catalog\/feature\/page\/store","name":"admin.catalog.feature.page.store"},{"uri":"dashboard\/catalog\/feature\/params\/view_product","name":"admin.catalog.feature.json.product"},{"uri":"dashboard\/catalog\/feature\/params\/view_filter","name":"admin.catalog.feature.json.filter"},{"uri":"dashboard\/catalog\/feature\/store","name":"admin.catalog.feature.store"},{"uri":"dashboard\/catalog\/feature\/delete","name":"admin.catalog.feature.delete"},{"uri":"dashboard\/catalog\/feature\/sortable","name":"admin.catalog.feature.sortable"},{"uri":"dashboard\/catalog\/feature\/sortable\/variants","name":"admin.catalog.feature.sortable.variants"},{"uri":"dashboard\/catalog\/feature-group","name":"admin.catalog.feature.group.home"},{"uri":"dashboard\/catalog\/feature-group\/edit\/{locale}\/{uuid?}","name":"admin.catalog.feature.group.edit"},{"uri":"dashboard\/catalog\/feature-group\/store","name":"admin.catalog.feature.group.store"},{"uri":"dashboard\/catalog\/feature-group\/delete","name":"admin.catalog.feature.group.delete"},{"uri":"dashboard\/catalog\/feature-group\/sortable","name":"admin.catalog.feature.group.sortable"},{"uri":"dashboard\/catalog\/product","name":"admin.catalog.product.home"},{"uri":"dashboard\/catalog\/product\/edit\/{locale}\/{uuid?}","name":"admin.catalog.product.edit"},{"uri":"dashboard\/catalog\/product\/copy","name":"admin.catalog.product.copy"},{"uri":"dashboard\/catalog\/product\/store","name":"admin.catalog.product.store"},{"uri":"dashboard\/catalog\/product\/delete","name":"admin.catalog.product.delete"},{"uri":"dashboard\/catalog\/product\/sortable","name":"admin.catalog.product.sortable"},{"uri":"dashboard\/catalog\/product\/sortable\/category\/{uuid}","name":"admin.catalog.product.sortable.category"},{"uri":"dashboard\/catalog\/product\/ajax\/update\/variant-features","name":"admin.catalog.product.ajax.update.feature"},{"uri":"dashboard\/catalog\/product\/ajax\/update\/params","name":"admin.catalog.product.ajax.update.params"},{"uri":"dashboard\/catalog\/variation\/{uuid}","name":"admin.catalog.variation.home"},{"uri":"dashboard\/catalog\/variation\/add","name":"admin.catalog.variation.add"},{"uri":"dashboard\/catalog\/variation\/create","name":"admin.catalog.variation.create"},{"uri":"dashboard\/catalog\/variation\/disband","name":"admin.catalog.variation.disband"},{"uri":"dashboard\/catalog\/variation\/remove-product","name":"admin.catalog.variation.remove.product"},{"uri":"dashboard\/user","name":"admin.user.home"},{"uri":"dashboard\/user\/edit\/{id}","name":"admin.user.edit"},{"uri":"dashboard\/user\/store","name":"admin.user.store"},{"uri":"dashboard\/user\/delete","name":"admin.user.delete"},{"uri":"dashboard\/widget","name":"admin.widget.home"},{"uri":"dashboard\/widget\/edit\/{uuid?}","name":"admin.widget.edit"},{"uri":"dashboard\/widget\/store","name":"admin.widget.store"},{"uri":"dashboard\/widget\/delete","name":"admin.widget.delete"},{"uri":"dashboard\/widget\/api","name":"admin.widget.api"},{"uri":"dashboard\/company","name":"admin.company.home"},{"uri":"dashboard\/company\/edit\/{locale}\/{uuid?}","name":"admin.company.edit"},{"uri":"dashboard\/company\/store","name":"admin.company.store"},{"uri":"dashboard\/company\/sortable","name":"admin.company.sortable"},{"uri":"dashboard\/company\/delete","name":"admin.company.delete"},{"uri":"dashboard\/setting\/website\/{locale}","name":"admin.setting.website.home"},{"uri":"dashboard\/setting\/website\/delete-file\/{locale}","name":"admin.setting.website.delete-file"},{"uri":"dashboard\/setting\/website\/store","name":"admin.setting.website.store"},{"uri":"dashboard\/setting\/clear-cache","name":"admin.setting.clear.cache"},{"uri":"dashboard\/video\/edit\/{locale}\/{uuid}","name":"admin.video.edit"},{"uri":"dashboard\/video\/reset\/{uuid}","name":"admin.video.reset"},{"uri":"dashboard\/video\/store","name":"admin.video.store"},{"uri":"dashboard\/video\/delete","name":"admin.video.delete"},{"uri":"dashboard\/video\/sortable","name":"admin.video.sortable"},{"uri":"dashboard\/template","name":"admin.template.home"},{"uri":"dashboard\/template\/edit","name":"admin.template.edit"},{"uri":"\/","name":"home"},{"uri":"search","name":"search"},{"uri":"sitemap.xml","name":"sitemap"},{"uri":"feedback","name":"feedback"},{"uri":"catalog\/filterCountProducts","name":"find_count_product"},{"uri":"catalog\/sortable","name":"category_sortable"},{"uri":"{catalog}","name":"catalog"},{"uri":"{product}","name":"product"},{"uri":"file\/download\/{uuid}","name":"download"},{"uri":"{slug}","name":"view"}],
    route: function(name, params) {
      var route = this.searchRoute(name),
          rootUrl = this.getRootUrl(),
          result = "",
          compiled = "";

      if (route) {
        compiled = this.buildParams(route, params);
        result = this.cleanupDoubleSlashes(rootUrl + '/' + compiled);
        result = this.stripTrailingSlash(result);
        return result;
      }

    },
    searchRoute: function(name) {
      for (var i = this.routes.length - 1; i >= 0; i--) {
        if (this.routes[i].name == name) {
          return this.routes[i];
        }
      }
    },
    buildParams: function(route, params) {
      var compiled = route.uri,
          queryParams = {};

      for (var key in params) {
        if (compiled.indexOf('{' + key + '?}') != -1) {
          if (key in params) {
            compiled = compiled.replace('{' + key + '?}', params[key]);
          }
        } else if (compiled.indexOf('{' + key + '}') != -1) {
          compiled = compiled.replace('{' + key + '}', params[key]);
        } else {
          queryParams[key] = params[key];
        }
      }

      compiled = compiled.replace(/\{([^\/]*)\?}/g, "");

      if (!this.isEmptyObject(queryParams)) {
        return compiled + this.buildQueryString(queryParams);
      }

      return compiled;
    },
    getRootUrl: function() {
      return window.location.protocol + '//' + window.location.host;
    },
    buildQueryString: function(params) {
      var ret = [];
      for (var key in params) {
        ret.push(encodeURIComponent(key) + "=" + encodeURIComponent(params[key]));
      }
      return '?' + ret.join("&");
    },
    isEmptyObject: function(obj) {
      var name;
      for (name in obj) {
        return false;
      }
      return true;
    },
    cleanupDoubleSlashes: function(url) {
      return url.replace(/([^:]\/)\/+/g, "$1");
    },
    stripTrailingSlash: function(url) {
      if(url.substr(-1) == '/') {
        return url.substr(0, url.length - 1);
      }
      return url;
    }
  };
}));