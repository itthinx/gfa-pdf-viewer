# gfa-pdf-viewer
Integrates Groups File Access and PDF Viewer providing the [gfapdfviewer] shortcode version of [pdfviewer] shortcode for files protected by Groups File Access.

Usage Example:

```
[gfapdfviewer width="600px" height="849px" beta="false" file_id="1" session_access="yes"][/pdfviewer]
```

The shortcode is a version of PDF Viewer's [pdfviewer] shortcode and accepts two required additional attributes:

- file_id : indicate the ID of the file protected by Groups File Access
- session_access : must be set to "yes" to allow rendering of the file through the PDF Viewer

