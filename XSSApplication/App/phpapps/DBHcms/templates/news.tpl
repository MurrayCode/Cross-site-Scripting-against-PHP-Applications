
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
	# $Id: news.tpl 68 2007-05-31 20:28:17Z kaisven $                                           #
	#############################################################################################
		
	-->
	
	<!-- BEGIN newsUnsubscribeMessage -->
		<br />
		<div align="center">
			<div class="simplebox">
				<div class="simplebox_caption" style="border-bottom-width: 0px;"> &nbsp; {dict_news_unsubscnl} !!! </div>
			</div>
		</div>
		<br />
	<!-- END newsUnsubscribeMessage -->
	
	<!-- BEGIN newsOverview -->
		<br />
		<!-- BEGIN newsArticleTeaser -->
			<div align="center">
				<div class="simplebox" align="left">
					<div class="simplebox_caption"> 
						&nbsp; {newsArticleTeaser.articleNewTag} 
						<a href="{newsArticleTeaser.articleUrl}"> 
							{newsArticleTeaser.articleParamTitle}
						</a> 
						&nbsp; [{newsArticleTeaser.articleCommentCount} {dict_comments}] 
					</div>
					<div style="padding:8px;">
						{newsArticleTeaser.articleParamTeaser}<br />
						[<a href="{newsArticleTeaser.articleUrl}">{dict_readmore} &raquo;]</a>
						<div align="right" style="font-size:8pt; color:#999999; ">{newsArticleTeaser.articleDate}</div>
					</div>
				</div>
			</div>
		<!-- END newsArticleTeaser -->
		
		<div align="center">{dict_page}: {newsOverview.newsJumplinks}</div>
		
	<!-- END newsOverview -->
	

	<!-- BEGIN newsArticle -->
		
		<div style="padding:15px; width:95%">
			<h1>{newsArticle.articleParamTitle}</h1>
			<h3>{newsArticle.articleParamSubtitle}</h3>
			<br />
			{newsArticle.articleParamContent}
		</div>
		<br />
	
	
		<!-- BEGIN newsArticleComments -->
		
			<div align="center">
				
				<br />								
				<hr noshade="noshade" />
				<font size="3"> <strong> - {dict_comments} - </strong> </font>
				<hr noshade="noshade" />
				<br />
			
				<!-- BEGIN newsArticleComment -->
				
					<div class="simplebox" align="left">
						<div class="simplebox_caption"> &nbsp; 
							{newsArticleComment.commentDelete} 
							{newsArticleComment.commentNewTag} 
							{newsArticleComment.commentEntryTitle} 
							{newsArticleComment.commentSexIcon} 
							{newsArticleComment.commentEmailIcon} 
							{newsArticleComment.commentWebsiteIcon} 
						</div>
						<div style="padding:8px;">
							{newsArticleComment.commentText}<div align="right" style="font-size:8pt; color:#999999;">{newsArticleComment.commentDate}</div>
						</div>
					</div>
					
				<!-- END newsArticleComment -->
				
				<!-- BEGIN newsArticleCommentNone -->
				<div class="simplebox">
					<div class="simplebox_caption" style="border-bottom-width: 0px;"> &nbsp; ... {dict_nocmnt}</div>
				</div>
				<!-- END newsArticleCommentNone -->
				
				<br />
				<div class="simplebox">
					<div class="simplebox_caption"> &nbsp; {dict_addcmnt} </div>
					<div style="padding:8px;">
						<form action="{newsArticle.articleUrl}" method="post" name="newsCommentForm" onSubmit="return check_comment();">
							<table border="0" width="500" align="center" cellspacing="0">
								<tr>
									<td align="right"><strong>{dict_name}:</strong>&nbsp;</td>
									<td align="left">
										<input type="text" name="newsName" maxlength="40" tabindex="1"  value="{str_userRealName}" />
									</td>
									<td align="right"><strong>{dict_email}:</strong>&nbsp;</td>
									<td align="left"><input type="text" name="newsEmail" maxlength="60" tabindex="3" value="{str_userEmail}" /></td>
								</tr>
								<tr>
							    	<td align="right"><strong>{dict_location}:</strong>&nbsp;</td>
						    		<td align="left"><input type="text" name="newsLocation" maxlength="40" tabindex="2" value="{str_userLocation}" /></td>
									<td align="right"><strong>{dict_homepage}:</strong>&nbsp;</td>
									<td align="left"><input type="text" name="newsHomepage" maxlength="100" tabindex="4" value="{str_userWebsite}" /></td>
								</tr>
								<tr>
									<td></td>
									<td colspan="2" align="left">
										<input style="border:0px;" type="radio" name="newsSex" value="ST_FEMALE" /> <strong>{dict_female}</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input style="border:0px;" type="radio" name="newsSex" value="ST_MALE" /> <strong>{dict_male}</strong> 
									</td>
								</tr>
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr>
									<td align="right" valign="top"><strong>{dict_message}:</strong>&nbsp;</td>
									<td colspan="3" align="left">
							    		<textarea name="newsText" cols="46" rows="7" lang="de" tabindex="5"></textarea>
								    </td>
								</tr>
								<tr>
									<td></td>
									<td colspan="3" align="left">
										{str_newsSmiliesBar}
					   			 	</td>
								</tr>
								<tr>
									<td height="30"></td>
									<td colspan="3" valign="bottom" align="left">
										<br />
										<input type="hidden" name="dbhcmsCache" value="CT_EMPTYPAGE" />
										<input type="hidden" name="todo" value="newsAddComment" />
										<input class="buttonsend"  type="submit" value=" {dict_addcmnt} >> " />
					   			 	</td>
								</tr>
							</table>
						</form>
					</div>
				</div>
			</div>
		<!-- END newsArticleComments -->
		
	<!-- END newsArticle -->
	
	<!-- BEGIN newsSignNewsletter -->
		<br />
		<div align="center">
			<div class="simplebox" align="left">
				<div class="simplebox_caption"> &nbsp; {dict_news_subscnl} </div>
				<div style="padding:8px;">
					<form acrion="{str_pageUrlWp}" method="post" name="newsSignNewsletter">
						<table>
							<tr>
								<td><strong>{dict_name}:</strong></td>
								<td><strong>{dict_email}:</strong></td>
								<td></td>
							</tr>
							<tr>
								<td><input type="text" name="newsSignFullname" value="{str_userRealName}" /></td>
								<td><input type="text" name="newsSignEmail" value="{str_userEmail}" /></td>
								<td>
									<input type="hidden" name="dbhcmsCache" value="CT_OFF" />
									<input type="hidden" name="todo" value="newsSubscribeNewsletter" />
									<input class="buttonsend"  type="submit" value=" {dict_news_subscnl} >> " />
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	<!-- END newsSignNewsletter -->