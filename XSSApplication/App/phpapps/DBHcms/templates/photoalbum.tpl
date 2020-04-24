
	<!--
	
	#############################################################################################
	#                                                                                           #
	#  DBHCMS - Web Content Management System                                                   #
	#                                                                                           #
	#############################################################################################
	#                                                                                           #
	#  COPYRIGHT NOTICE                                                                         #
	#  =============================                                                            #
	#                                                                                           #
	#  Copyright (C) 2005-2007 Kai-Sven Bunk (kaisven@drbenhur.com)                             #
	#  All rights reserved                                                                      #
	#                                                                                           #
	#  This file is part of DBHcms.                                                             #
	#                                                                                           #
	#  DBHcms is free software; you can redistribute it and/or modify it under the terms of     #
	#  the GNU General Public License as published by the Free Software Foundation; either      #
	#  version 2 of the License, or (at your option) any later version.                         #
	#                                                                                           #
	#  The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html      #
	#  A copy is found in the textfile GPL.TXT                                                  #
	#                                                                                           #
	#  DBHcms is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;      #
	#  without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR         #
	#  PURPOSE. See the GNU General Public License for more details.                            #
	#                                                                                           #
	#  This copyright notice MUST APPEAR in ALL copies of the script!                           #
	#                                                                                           #
	#############################################################################################
	# $Id: photoalbum.tpl 68 2007-05-31 20:28:17Z kaisven $                                     #
	#############################################################################################
		
	-->
	
	<br />
				
	<!-- BEGIN photoalbumOverview -->
	
		<div align="center">
		
			<!-- BEGIN photoalbumAlbum -->	
				
				<div class="simplebox">
					<div class="simplebox_caption"> &nbsp; 
						{photoalbumAlbum.albumNewTag} &nbsp; 
						<a href="{photoalbumAlbum.albumPicsUrl}"> 
							{photoalbumAlbum.albumDate} :: {photoalbumAlbum.albumParamTitle} 
						</a> 
					</div>
					<table border="0" cellpadding="2" cellspacing="0" align="center" width="95%">
						<tr>
							<td width="100">
								<a href="{photoalbumAlbum.albumPicsUrl}"><img src="{photoalbumAlbum.albumThumbnail}" style="border-style: solid; border-width: 1px; border-color: #000000;"></a>
							</td>
							<td width="400">
								<table cellspacing="0" cellpadding="0" border="0" width="400" align="center">
									<tr><td colspan="2" height="10"></td></tr>
									<tr>
										<td valign="top" height="23" width="110" align="right">
											<strong>{dict_photoalbum_presence} : </strong>
										</td>
										<td valign="top" width="290" align="left">
											<font face="Verdana" style="font-size:11px"> &nbsp; {photoalbumAlbum.albumParamPresence}</font>
										</td>
									</tr>
									<tr>
										<td valign="top" height="23" align="right">
											<strong>{dict_photoalbum_activities} : </strong>
										</td>
										<td valign="top" align="left">
											<font face="Verdana" style="font-size:11px"> &nbsp; {photoalbumAlbum.albumParamActivities}</font>
										</td>
									</tr>
									<tr>
										<td valign="top" height="23" align="right">
											<strong>{dict_photoalbum_location} : </strong>
										</td>
										<td valign="top" align="left">
											<font face="Verdana" style="font-size:11px"> &nbsp; {photoalbumAlbum.albumParamLocation}</font>
										</td>
									</tr>
									<tr>
										<td valign="top" height="23" align="right">
											<strong>{dict_details} : </strong>
										</td>
										<td valign="top" align="left">
											&nbsp;  <img src="{str_imageDirectory}other/comments.gif" align="absmiddle" width="16" height="12" alt="" border="0">:{photoalbumAlbum.albumCommentCount} &nbsp;
													<img src="{str_imageDirectory}other/images.gif" align="absmiddle" width="13" height="10" alt="" border="0">:{photoalbumAlbum.albumPictureCount} &nbsp;
													<img src="{str_imageDirectory}other/clips.gif" align="absmiddle" width="14" height="10" alt="" border="0">:{photoalbumAlbum.albumVideoCount}
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
				
			<!-- END photoalbumAlbum -->

			{dict_page}: {photoalbumOverview.albumJumplinks}
			
		</div>
		
	<!-- END photoalbumOverview -->
	
	
	<!-- BEGIN photoalbumAlbumOverview -->
		<div align="center">
			<h2>{photoalbumAlbumOverview.albumParamTitle} ({photoalbumAlbumOverview.albumDate})</h2>
		</div>
		<table border="0" cellpadding="2" cellspacing="0" align="center" width="95%">
			<tr>
				<!-- BEGIN photoalbumAlbumPics -->
					<td align="center" valign="top">
						<div class="simplebox" style="height: 200px;">
							<div class="simplebox_caption"> &nbsp; 
								{photoalbumAlbumPics.picDelete} 
								[{photoalbumAlbumPics.picCommentCount} <img src="{str_imageDirectory}other/comments.gif"  alt="{photoalbumAlbumPics.picCommentCount} {dict_comments}" border="0" align="absmiddle">] 
								<img src="{str_imageDirectory}other/rating_{photoalbumAlbumPics.picRating}.gif" width="56" height="12" alt="{photoalbumAlbumPics.picRating}/5 ({photoalbumAlbumPics.picRatingCount} {dict_votes})" border="0">   
							</div>
							<br />
							<a href="{photoalbumAlbumPics.picUrl}">
								<img width="200" src="{photoalbumAlbumPics.picImg}" style="border-style: solid; border-width: 1px; border-color: #000000;">
							</a>
							{photoalbumAlbumPics.picUserLevelChange}
							<br />
							<br />
						</div>
					</td>
					{photoalbumAlbumPics.picNewRow}
				<!-- END photoalbumAlbumPics -->
			</tr>
		</table>
		<br />
		<div align="center">
			{dict_page}: {photoalbumAlbumOverview.albumJumplinks}
		</div>
	<!-- END photoalbumAlbumOverview -->
	
	
	<!-- BEGIN photoalbumShowPic -->
	
		<table border="0" cellpadding="2" cellspacing="0" align="center" width="95%">
			<tr>
				<td>
					<div class="simplebox" style="width:100%;">
						<div class="simplebox_caption" style="width:100%; text-align: center; border-bottom: 0px;"> 
							<a href="{photoalbumShowPic.picFirstUrl}"> &#171; {dict_firstpic} </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="{photoalbumShowPic.picPreviousUrl}"> &#8249; {dict_previouspic} </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="{photoalbumShowPic.picNextUrl}"> {dict_nextpic} &#8250; </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="{photoalbumShowPic.picLastUrl}"> {dict_lastpic} &#187; </a>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td valign="top" align="center">
					{photoalbumShowPic.picObject}
				</td>
			</tr>
			<tr>
				<td>
					<br />
					<div class="simplebox" style="width:100%;">
						<div class="simplebox_caption" style="width:100%; text-align: center; border-bottom: 0px;"> 
							<a href="{photoalbumShowPic.picFirstUrl}"> &#171; {dict_firstpic} </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="{photoalbumShowPic.picPreviousUrl}"> &#8249; {dict_previouspic} </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="{photoalbumShowPic.picNextUrl}"> {dict_nextpic} &#8250; </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="{photoalbumShowPic.picLastUrl}"> {dict_lastpic} &#187; </a>
						</div>
					</div>
				</td>
			</tr>
		</table>
		
		<!-- BEGIN photoalbumPicRating -->
			
			
			<br />								
			<hr noshade="noshade" />
			<div align="center">
				<font size="3"> 
					<strong> - {dict_rate} - </strong> 
				</font>
			</div>
			<hr noshade="noshade" />
			<br />
			
			<form action="{str_pageUrlWp}" method="post" name="photoalbumAddPicRating" onSubmit="return check_rating()">
				<input type="hidden" name="picId" value="{photoalbumShowPic.picId}">
				
				<table border="0" cellpadding="2" cellspacing="0" align="center" width="95%">
					<tr>
						<td>
							<div class="simplebox" style="width:100%; align: center;">
								<div class="simplebox_caption" style="width:100%; text-align: center; border-bottom: 0px;"> 
									<table align="center" cellspacing="0" cellpadding="2" border="0" width="95%" >
										<tr>						
											<td align="center"><input style="border:0px; width:10px;" type="radio" value="1" name="ratingNr"> <a onclick="document.photoalbumAddPicRating.ratingNr[0].checked = true;" style="cursor:pointer;"><img src="{str_imageDirectory}other/rating_1.gif" width="56" height="12" alt="" border="0"></a></td>
											<td align="center"><input style="border:0px; width:10px;" type="radio" value="2" name="ratingNr"> <a onclick="document.photoalbumAddPicRating.ratingNr[1].checked = true;" style="cursor:pointer;"><img src="{str_imageDirectory}other/rating_2.gif" width="56" height="12" alt="" border="0"></a></td>
											<td align="center"><input style="border:0px; width:10px;" type="radio" value="3" name="ratingNr"> <a onclick="document.photoalbumAddPicRating.ratingNr[2].checked = true;" style="cursor:pointer;"><img src="{str_imageDirectory}other/rating_3.gif" width="56" height="12" alt="" border="0"></a></td>
											<td align="center"><input style="border:0px; width:10px;" type="radio" value="4" name="ratingNr"> <a onclick="document.photoalbumAddPicRating.ratingNr[3].checked = true;" style="cursor:pointer;"><img src="{str_imageDirectory}other/rating_4.gif" width="56" height="12" alt="" border="0"></a></td>							
											<td align="center"><input style="border:0px; width:10px;" type="radio" value="5" name="ratingNr"> <a onclick="document.photoalbumAddPicRating.ratingNr[4].checked = true;" style="cursor:pointer";><img src="{str_imageDirectory}other/rating_5.gif" width="56" height="12" alt="" border="0"></a></td>
											<td align="right"><input type="hidden" name="dbhcmsCache" value="CT_EMPTYPAGE" /><input type="hidden" name="todo" value="photoalbumAddPicRating">&nbsp;&nbsp;<input style="width: 100px;"  type="submit" value="   {dict_rate}   ">&nbsp;&nbsp;</td>
										<tr>
									</table>
								</div>
							</div>
						</td>
					</tr>
				</table>
				
			</form>	
		
		<!-- END photoalbumPicRating -->
		
		<!-- BEGIN photoalbumPicComments -->
			
			<br />								
			<hr noshade="noshade" />
			<div align="center">
				<font size="3"> 
					<strong> - {dict_comments} - </strong> 
				</font>
			</div>
			<hr noshade="noshade" />
			<br />	
			
			<table border="0" cellpadding="2" cellspacing="0" align="center" width="95%">
				<tr>
					<td valign="top" align="left">
						
						<!-- BEGIN photoalbumPicComment -->
							<div class="simplebox" style="width:100%;">
								<div class="simplebox_caption"> &nbsp; 
									{photoalbumPicComment.commentDelete} 
									{photoalbumPicComment.commentNewTag} 
									{photoalbumPicComment.commentEntryTitle} 
									{photoalbumPicComment.commentSexIcon} 
									{photoalbumPicComment.commentEmailIcon} 
									{photoalbumPicComment.commentWebsiteIcon} 
								</div>
								<div style="padding:8px;">
									{photoalbumPicComment.commentText}<div align="right" style="font-size:8pt; color:#999999; ">{photoalbumPicComment.commentDate}</div>
								</div>
							</div>
						<!-- END photoalbumPicComment -->
						
						<!-- BEGIN photoalbumPicCommentsNone -->
							<div class="simplebox" style="width:100%;">
								<div class="simplebox_caption" style="border-bottom-width: 0px;"> &nbsp; ... {dict_nocmnt}</div>
							</div>
						<!-- END photoalbumPicCommentsNone -->
						
					</td>
				</tr>
				<tr>
					<td align="left">
						<br />
						<div class="simplebox" style="width:100%;">
							<div class="simplebox_caption"> &nbsp; {dict_addcmnt} </div>
							<div style="padding:8px;">
								<form action="{str_pageUrlWp}" method="post" name="photoalbumAddPicComment" onSubmit="return check_comment();">
									<input type="hidden" name="picId" value="{photoalbumShowPic.picId}">
									<table border="0" width="500" align="center" cellspacing="0">
										<tr>
											<td align="right"><strong>{dict_name}:</strong>&nbsp;</td>
											<td align="left">
												<input type="text" name="photoalbumName" maxlength="40" tabindex="1"  value="{str_userRealName}">
											</td>
											<td align="right"><strong>{dict_email}:</strong>&nbsp;</td>
											<td align="left"><input type="text" name="photoalbumEmail" maxlength="60" tabindex="3" value="{str_userEmail}"></td>
										</tr>
										<tr>
									    	<td align="right"><strong>{dict_location}:</strong>&nbsp;</td>
								    		<td align="left"><input type="text" name="photoalbumLocation" maxlength="40" tabindex="2" value="{str_userLocation}"></td>
											<td align="right"><strong>{dict_homepage}:</strong>&nbsp;</td>
											<td align="left"><input type="text" name="photoalbumHomepage" maxlength="100" tabindex="4" value="{str_userWebsite}"></td>
										</tr>
										<tr>
											<td></td>
											<td colspan="2" align="left">
												<strong>{dict_female}:</strong> <input style="border:0px;" type="radio" name="photoalbumSex" value="ST_FEMALE"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>{dict_male}:</strong><input style="border:0px;" type="radio" name="photoalbumSex" value="ST_MALE"> 
											</td>
										</tr>
										<tr><td colspan="2">&nbsp;</td></tr>
										<tr>
											<td align="right" valign="top"><strong>{dict_message}:</strong>&nbsp;</td>
											<td colspan="3" align="left">
									    		<textarea name="photoalbumText" cols="46" rows="7" lang="de" tabindex="5"></textarea>
										    </td>
										</tr>
										<tr>
											<td></td>
											<td colspan="3">
												{str_photoalbumSmiliesBar}
							   			 	</td>
										</tr>
										<tr>
											<td height="30"></td>
											<td colspan="3" valign="bottom" align="left">
												<br />
												<input type="hidden" name="dbhcmsCache" value="CT_EMPTYPAGE" />
												<input type="hidden" name="todo" value="photoalbumAddPicComment">
												<input class="buttonsend"  type="submit" value=" {dict_addcmnt} >> ">
							   			 	</td>
										</tr>
									</table>
								</form>
							</div>
						</div>
						<br />
					</td>
				</tr>
			</table>
		
		<!-- END photoalbumPicComments -->
	
	<!-- END photoalbumShowPic -->
				
	<br />

