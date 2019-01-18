var querystring = require('querystring')
var xhr = require('xhr')

if (!xhr.open) xhr = require('request')

module.exports = function (ids, opts, cb) {
  if (typeof opts === 'function') {
    cb = opts
    opts = {}
  }

  var params = {
    part: "contentDetails, snippet",
    id: ids.join(", ")
  }

	Object.keys(opts).map(function (k) {
		params[k] = opts[k]
	});

  xhr({
    url: 'https://www.googleapis.com/youtube/v3/videos?' + querystring.stringify(params),
    method: 'GET'
  }, function (err, res, body) {
    if (err) return cb(err)

    try {
      var result = JSON.parse(body)

      if (result.error) {
        var error = new Error(result.error.errors.shift().message)
        return cb(error)
      }

      var pageInfo = {
        totalResults: result.pageInfo.totalResults,
        resultsPerPage: result.pageInfo.resultsPerPage,
        nextPageToken: result.nextPageToken,
        prevPageToken: result.prevPageToken
      }

      /*
      var findings = result.items.map(function (item) {
        return {
          kind: item.id.kind,
          publishedAt: item.snippet.publishedAt,
          channelId: item.snippet.channelId,
          channelTitle: item.snippet.channelTitle,
          title: item.snippet.title,
          description: item.snippet.description,
          thumbnails: item.snippet.thumbnails
        }
      })
      */

      var findings = result.items;
      
      return cb(null, findings, pageInfo)
    } catch(e) {
      return cb(e)
    }
  })
}
