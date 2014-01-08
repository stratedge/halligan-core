<?php

namespace Halligan;

use ErrorException;

class Error {

	public static function renderException($exception, $show_trace = TRUE)
	{
		if(URI::isCLI())
		{
			self::renderErrorForConsole($exception, $show_trace);
		}
		else
		{
			self::renderErrorForWeb($exception, $show_trace);
		}
	}


	//---------------------------------------------------------------------------------------------
	

	public static function renderError($code, $error, $file, $line)
	{
		$exception = new ErrorException($error, $code, 0, $file, $line);

		static::renderException($exception);
	}


	//---------------------------------------------------------------------------------------------
	

	public static function renderShutdown()
	{
		$error = error_get_last();

		if(!is_null($error))
		{
			extract($error, EXTR_SKIP);

			static::renderException(new ErrorException($message, $type, 0, $file, $line), FALSE);
		}
	}


	//---------------------------------------------------------------------------------------------
	

	public static function renderErrorForWeb($exception, $show_trace = TRUE)
	{
		$response = "<html>
			<h2>Unhandled Exception</h2>
			<h3>Message:</h3>
			<pre>" . $exception->getMessage() . "</pre>
			<h3>Location:</h3>
			<pre>" . $exception->getFile() . " on line " . $exception->getLine() . "</pre>";

		if($show_trace)
		{
			$trace = '';

			$trace_str = "
				<tr>
					<td><pre>%d</pre></td>
					<td><pre>%s</pre></td>
					<td><pre>%s</pre></td>
					<td><pre>%s</pre></td>
					<td><pre>%s</pre></td>
				</tr>";

			foreach($exception->getTrace() as $key => $item)
			{
				$trace .= sprintf(
					$trace_str,
					$key + 1,
					isset($item['line']) ? $item['line'] : '---',
					isset($item['class']) ? $item['class'] : '---',
					isset($item['function']) ? $item['function'] . "()" : '---',
					isset($item['file']) ? $item['file'] : '---'
				);
			}

			$response .= "<h3>Trace:</h3>
				<table>
					<tr>
						<th style=\"border-bottom: 1px solid black;\">#</th>
						<th style=\"border-bottom: 1px solid black;\">Line</th>
						<th style=\"border-bottom: 1px solid black;\">Class</th>
						<th style=\"border-bottom: 1px solid black;\">Function</th>
						<th style=\"border-bottom: 1px solid black;\">File</th>
					</tr>" . $trace . "
				</table>";
		}

		$response .= "</html>";

		ob_clean();
		
		ob_start();

		echo $response;
		
		exit(ob_get_clean());
	}

	public static function renderErrorForConsole($exception, $show_trace = TRUE)
	{
		Console::writeLine("Unhandled Exception");
		Console::writeLine(sprintf("Message: %s", $exception->getMessage()));
		Console::writeLine(sprintf("Location: %s on line %d", $exception->getFile(), $exception->getLine()));
		Console::writeLine();

		if($show_trace)
		{
			$headers = array("#", "Line", "Class", "Function", "File");

			$trace = array();
			
			foreach($exception->getTrace() as $key => $item)
			{
				$trace[] = array(
					$key + 1,
					isset($item["line"]) ? $item["line"] : "---",
					isset($item["class"]) ? $item["class"] : "---",
					isset($item["function"]) ? $item["function"] : "---",
					isset($item["file"]) ? $item["file"] : "---"
				);
			}

			Console::writeTable($trace, $headers);
		}

		exit();
	}

}

/* End of file Error.php */
/* Location: ./Halligan/Error.php */