<!--------------------------------------------------------------------
	SmartClient SDK
	Demo Application (JS code) + resizeBars

	Copyright 2005 Isomorphic Software, Inc. (www.isomorphic.com)
---------------------------------------------------------------------->

<%@ taglib uri="/WEB-INF/iscTaglib.xml" prefix="isomorphic" %>

<HTML><HEAD><TITLE>SmartClient Demo Application</TITLE>
<!--  -->
<isomorphic:loadISC skin="SmartClient" runat="server"/>
</HEAD><BODY BGCOLOR=#000040>

<!-- tabPane tweaks no edges, no borders tab panes, below a 4px baseline -->
<STYLE>
.demoTabSet {
	margin-top:2px;
}
.demoTabPane {
	margin-top:4px;
}
</STYLE>
<!----- Load custom overrides for skinning this application ----->
<LINK REL="stylesheet" TYPE="text/css" HREF="demoApp_styles.css">
<SCRIPT SRC=demoApp_skinOverrides.js></SCRIPT>

<SCRIPT>


// Load DataSources
// ---------------------------------------------------------------------

<isomorphic:loadDS name="supplyItem"/>
<isomorphic:loadDS name="supplyCategory"/>

// Set up the app img dir so we pick up the example's images
isc.Page.setAppImgDir(isc.Page.getIsomorphicDocsDir()+"exampleImages/");
</SCRIPT>

<!--  Pick up application UI and logic from the .js UI file
  ======================================================================
-->
<script src="demoAppJS.js"></script>
</BODY>
</HTML>
