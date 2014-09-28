<?php
/**
* @version		$Id: admin.templates.html.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @subpackage	Templates
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* @package		Joomla
* @subpackage	Templates
*/
class TemplatesView
{
	/**
	* @param array An array of data objects
	* @param object A page navigation object
	* @param string The option
	*/
	public static function showTemplates(& $rows, & $lists, & $page, $option, & $client)
	{
		global $mainframe;

		$limitstart = JRequest :: getVar('limitstart', '0', '', 'int');

		$user = & JFactory :: getUser();

		if (isset ($row->authorUrl) && $row->authorUrl != '') {
			$row->authorUrl = str_replace('http://', '', $row->authorUrl);
		}

		JHTML::_('behavior.tooltip');
?>
		<form action="index.php" method="post" name="adminForm">

			<table class="adminlist">
			<thead>
				<tr>
					<th width="5" class="title">
						<?php echo JText::_( 'Num' ); ?>
					</th>
					<th class="title" colspan="2">
						<?php echo JText::_( 'Template Name' ); ?>
					</th>
					<?php

		if ($client->id == 1) {
?>
						<th width="5%">
							<?php echo JText::_( 'Default' ); ?>
						</th>
						<?php

		} else {
?>
						<th width="5%">
							<?php echo JText::_( 'Default' ); ?>
						</th>
						<th width="5%">
							<?php echo JText::_( 'Assigned' ); ?>
						</th>
						<?php

		}
?>
					<th width="10%" align="center">
						<?php echo JText::_( 'Version' ); ?>
					</th>
					<th width="15%" class="title">
						<?php echo JText::_( 'Date' ); ?>
					</th>
					<th width="25%"  class="title">
						<?php echo JText::_( 'Author' ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8">
						<?php echo $page->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php

		$k = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = & $rows[$i];

			$author_info = @ $row->authorEmail . '<br />' . @ $row->authorUrl;
?>
				<tr class="<?php echo 'row'. $k; ?>">
					<td>
						<?php echo $page->getRowOffset( $i ); ?>
					</td>
					<td width="5">
					<?php

			if ( JTable::isCheckedOut($user->get ('id'), $row->checked_out )) {
?>
							&nbsp;
							<?php

			} else {
?>
							<input type="radio" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->directory; ?>" onclick="isChecked(this.checked);" />
							<?php

			}
?>
					</td>
					<td><?php $img_path = ($client->id == 1 ? JURI::root().'administrator' : JURI::root() ).'/templates/'.$row->directory.'/template_thumbnail.png'; ?>
						<span class="editlinktip hasTip" title="<?php echo $row->name;?>::
<img border=&quot;1&quot; src=&quot;<?php echo $img_path; ?>&quot; name=&quot;imagelib&quot; alt=&quot;<?php echo JText::_( 'No preview available' ); ?>&quot; width=&quot;206&quot; height=&quot;145&quot; />"><a href="index.php?option=com_templates&amp;task=edit&amp;cid[]=<?php echo $row->directory;?>&amp;client=<?php echo $client->id;?>">
							<?php echo $row->name;?></a></span>
					</td>
					<?php

			if ($client->id == 1) {
?>
						<td align="center">
							<?php

				if ($row->published == 1) {
?>
							<img src="templates/rt_missioncontrol_j15/images/bullet-star.png" alt="<?php echo JText::_( 'Published' ); ?>" />
								<?php

				} else {
?>
								&nbsp;
								<?php

				}
?>
						</td>
						<?php

			} else {
?>
						<td align="center">
							<?php

				if ($row->published == 1) {
?>
								<img src="templates/rt_missioncontrol_j15/images/bullet-star.png" alt="<?php echo JText::_( 'Default' ); ?>" />
								<?php

				} else {
?>
								&nbsp;
								<?php

				}
?>
						</td>
						<td align="center">
							<?php

				if ($row->assigned == 1) {
?>
								<img src="images/tick.png" alt="<?php echo JText::_( 'Assigned' ); ?>" />
								<?php

				} else {
?>
								&nbsp;
								<?php

				}
?>
						</td>
						<?php

			}
?>
					<td align="center">
						<?php echo $row->version; ?>
					</td>
					<td>
						<?php echo $row->creationdate; ?>
					</td>
					<td>
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'Author Information' );?>::<?php echo $author_info; ?>">
							<?php echo @$row->author != '' ? $row->author : '&nbsp;'; ?>
						</span>
					</td>
				</tr>
				<?php

		}
?>
			</tbody>
			</table>

	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="client" value="<?php echo $client->id;?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<?php

	}

	/**
	* @param string Template name
	* @param string Source code
	* @param string The option
	*/
	public static function editTemplate($row, $lists, & $params, $option, & $client, & $ftp, & $template)
	{
		JRequest::setVar( 'hidemainmenu', 1 );
		JHTML::_('behavior.tooltip');
?>
		<form action="index.php" method="post" name="adminForm">

		<?php if($ftp): ?>
		<fieldset title="<?php echo JText::_('DESCFTPTITLE'); ?>" class="adminform">
			<legend><?php echo JText::_('DESCFTPTITLE'); ?></legend>

			<?php echo JText::_('DESCFTP'); ?>

			<?php if(JError::isError($ftp)): ?>
				<p><?php echo JText::_($ftp->message); ?></p>
			<?php endif; ?>

			<table class="adminform nospace">
			<tbody>
			<tr>
				<td width="120">
					<label for="username"><?php echo JText::_('Username'); ?>:</label>
				</td>
				<td>
					<input type="text" id="username" name="username" class="input_box" size="70" value="" />
				</td>
			</tr>
			<tr>
				<td width="120">
					<label for="password"><?php echo JText::_('Password'); ?>:</label>
				</td>
				<td>
					<input type="password" id="password" name="password" class="input_box" size="70" value="" />
				</td>
			</tr>
			</tbody>
			</table>
		</fieldset>
		<?php endif; ?>

		<div class="col width-50">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Details' ); ?></legend>

				<table class="admintable">
				<tr>
					<td valign="top" class="key">
						<?php echo JText::_( 'Name' ); ?>:
					</td>
					<td>
						<strong>
							<?php echo JText::_($row->name); ?>
						</strong>
					</td>
				</tr>
				<tr>
					<td valign="top" class="key">
						<?php echo JText::_( 'Description' ); ?>:
					</td>
					<td>
						<?php echo JText::_($row->description); ?>
					</td>
				</tr>
				</table>
			</fieldset>	
		</div>

		<div class="col width-50">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Parameters' ); ?></legend>
				<?php $templatefile = DS.'templates'.DS.$template.DS.'params.ini';
				$file = $client->path.$templatefile;
				if ( file_exists($file) && !is_writable($client->path.$templatefile) ) {
                      print 'params.ini file is not wriable';
                }  
                ?>            
				<table class="admintable">
				<tr>
					<td>
						<?php

		if (!is_null($params)) {
			echo $params->render();
		} else {
			echo '<i>' . JText :: _('No Parameters') . '</i>';
		}
?>
					</td>
				</tr>
				</table>
			</fieldset>
		</div>
		<div class="clr"></div>

		<input type="hidden" name="id" value="<?php echo $row->directory; ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="client" value="<?php echo $client->id;?>" />
		<input type="hidden" name="default" value="<?php echo $row->default; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}
}
